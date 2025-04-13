<?php

namespace App\Repositories\Eloquent;

use App\Models\AuditLog;
use App\Repositories\Interfaces\AuditLogRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogRepository implements AuditLogRepositoryInterface
{
    /**
     * Log a created event
     * 
     * @param Model $model The model that was created
     * @param array $tags Optional tags for categorization
     * @return AuditLog
     */
    public function logCreated(Model $model, array $tags = []): AuditLog
    {
        return $this->createLog(
            'created',
            $model,
            [], // No old values for creation
            $model->getAttributes(),
            $tags
        );
    }
    
    /**
     * Log an updated event
     * 
     * @param Model $model The model that was updated
     * @param array $oldValues The old values before the update
     * @param array $tags Optional tags for categorization
     * @return AuditLog
     */
    public function logUpdated(Model $model, array $oldValues, array $tags = []): AuditLog
    {
        return $this->createLog(
            'updated',
            $model,
            $oldValues,
            $model->getAttributes(),
            $tags
        );
    }
    
    /**
     * Log a deleted event
     * 
     * @param Model $model The model that was deleted
     * @param array $tags Optional tags for categorization
     * @return AuditLog
     */
    public function logDeleted(Model $model, array $tags = []): AuditLog
    {
        return $this->createLog(
            'deleted',
            $model,
            $model->getAttributes(),
            [], // No new values for deletion
            $tags
        );
    }
    
    /**
     * Log a restored event
     * 
     * @param Model $model The model that was restored
     * @param array $tags Optional tags for categorization
     * @return AuditLog
     */
    public function logRestored(Model $model, array $tags = []): AuditLog
    {
        return $this->createLog(
            'restored',
            $model,
            [], // Typically no old values for restoration
            $model->getAttributes(),
            $tags
        );
    }
    
    /**
     * Log a custom event
     * 
     * @param string $event The event name
     * @param Model $model The associated model
     * @param array $oldValues Optional old values
     * @param array $newValues Optional new values
     * @param array $tags Optional tags for categorization
     * @return AuditLog
     */
    public function logCustomEvent(string $event, Model $model, array $oldValues = [], array $newValues = [], array $tags = []): AuditLog
    {
        return $this->createLog(
            $event,
            $model,
            $oldValues,
            $newValues,
            $tags
        );
    }
    
    /**
     * Get audit logs for a specific model
     * 
     * @param Model $model The model to get logs for
     * @param int $perPage Number of items per page
     * @return LengthAwarePaginator
     */
    public function getLogsForModel(Model $model, int $perPage = 15): LengthAwarePaginator
    {
        return AuditLog::where('auditable_type', get_class($model))
            ->where('auditable_id', $model->getKey())
            ->latest()
            ->paginate($perPage);
    }
    
    /**
     * Get all audit logs with pagination
     * 
     * @param int $perPage Number of items per page
     * @param array $filters Optional filters
     * @return LengthAwarePaginator
     */
    public function getAllLogs(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = AuditLog::query()->latest();
        
        // Apply filters if provided
        if (!empty($filters)) {
            if (isset($filters['store_id'])) {
                $query->where('store_id', $filters['store_id']);
            }
            
            if (isset($filters['event'])) {
                $query->where('event', $filters['event']);
            }
            
            if (isset($filters['auditable_type'])) {
                $query->where('auditable_type', $filters['auditable_type']);
            }
            
            if (isset($filters['user_id'])) {
                $query->where('user_id', $filters['user_id']);
            }
            
            if (isset($filters['date_from'])) {
                $query->whereDate('created_at', '>=', $filters['date_from']);
            }
            
            if (isset($filters['date_to'])) {
                $query->whereDate('created_at', '<=', $filters['date_to']);
            }
        }
        
        return $query->paginate($perPage);
    }
    
    /**
     * Get logs by user
     * 
     * @param int $userId The user ID
     * @param int $perPage Number of items per page
     * @return LengthAwarePaginator
     */
    public function getLogsByUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return AuditLog::where('user_id', $userId)
            ->latest()
            ->paginate($perPage);
    }
    
    /**
     * Get logs by event type
     * 
     * @param string $event The event type
     * @param int $perPage Number of items per page
     * @return LengthAwarePaginator
     */
    public function getLogsByEvent(string $event, int $perPage = 15): LengthAwarePaginator
    {
        return AuditLog::where('event', $event)
            ->latest()
            ->paginate($perPage);
    }
    
    /**
     * Get logs by tags
     * 
     * @param array $tags The tags to filter by
     * @param int $perPage Number of items per page
     * @return LengthAwarePaginator
     */
    public function getLogsByTags(array $tags, int $perPage = 15): LengthAwarePaginator
    {
        return AuditLog::where(function ($query) use ($tags) {
            foreach ($tags as $tag) {
                $query->orWhereJsonContains('tags', $tag);
            }
        })
        ->latest()
        ->paginate($perPage);
    }
    
    /**
     * Helper method to create an audit log entry
     * 
     * @param string $event The event name
     * @param Model $model The affected model
     * @param array $oldValues The old values
     * @param array $newValues The new values
     * @param array $tags Optional tags
     * @return AuditLog
     */
    protected function createLog(string $event, Model $model, array $oldValues, array $newValues, array $tags): AuditLog
    {
        $user = Auth::user();
        
        $data = [
            'store_id' => method_exists($model, 'getStoreId') ? $model->getStoreId() : null,
            'user_id' => $user ? $user->id : null,
            'user_type' => $user ? get_class($user) : null,
            'event' => $event,
            'auditable_type' => get_class($model),
            'auditable_id' => $model->getKey(),
            'old_values' => empty($oldValues) ? null : $oldValues,
            'new_values' => empty($newValues) ? null : $newValues,
            'url' => Request::fullUrl(),
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
            'tags' => empty($tags) ? null : $tags,
        ];
        
        return AuditLog::create($data);
    }
} 