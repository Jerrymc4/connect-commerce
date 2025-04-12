<?php

namespace App\Repositories\Interfaces;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface AuditLogRepositoryInterface
{
    /**
     * Log a created event
     * 
     * @param Model $model The model that was created
     * @param array $tags Optional tags for categorization
     * @return AuditLog
     */
    public function logCreated(Model $model, array $tags = []): AuditLog;
    
    /**
     * Log an updated event
     * 
     * @param Model $model The model that was updated
     * @param array $oldValues The old values before the update
     * @param array $tags Optional tags for categorization
     * @return AuditLog
     */
    public function logUpdated(Model $model, array $oldValues, array $tags = []): AuditLog;
    
    /**
     * Log a deleted event
     * 
     * @param Model $model The model that was deleted
     * @param array $tags Optional tags for categorization
     * @return AuditLog
     */
    public function logDeleted(Model $model, array $tags = []): AuditLog;
    
    /**
     * Log a restored event
     * 
     * @param Model $model The model that was restored
     * @param array $tags Optional tags for categorization
     * @return AuditLog
     */
    public function logRestored(Model $model, array $tags = []): AuditLog;
    
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
    public function logCustomEvent(string $event, Model $model, array $oldValues = [], array $newValues = [], array $tags = []): AuditLog;
    
    /**
     * Get audit logs for a specific model
     * 
     * @param Model $model The model to get logs for
     * @param int $perPage Number of items per page
     * @return LengthAwarePaginator
     */
    public function getLogsForModel(Model $model, int $perPage = 15): LengthAwarePaginator;
    
    /**
     * Get all audit logs with pagination
     * 
     * @param int $perPage Number of items per page
     * @param array $filters Optional filters
     * @return LengthAwarePaginator
     */
    public function getAllLogs(int $perPage = 15, array $filters = []): LengthAwarePaginator;
    
    /**
     * Get logs by user
     * 
     * @param int $userId The user ID
     * @param int $perPage Number of items per page
     * @return LengthAwarePaginator
     */
    public function getLogsByUser(int $userId, int $perPage = 15): LengthAwarePaginator;
    
    /**
     * Get logs by event type
     * 
     * @param string $event The event type
     * @param int $perPage Number of items per page
     * @return LengthAwarePaginator
     */
    public function getLogsByEvent(string $event, int $perPage = 15): LengthAwarePaginator;
    
    /**
     * Get logs by tags
     * 
     * @param array $tags The tags to filter by
     * @param int $perPage Number of items per page
     * @return LengthAwarePaginator
     */
    public function getLogsByTags(array $tags, int $perPage = 15): LengthAwarePaginator;
} 