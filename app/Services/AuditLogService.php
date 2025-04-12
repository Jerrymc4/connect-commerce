<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Repositories\Interfaces\AuditLogRepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class AuditLogService
{
    protected AuditLogRepositoryInterface $repository;
    
    /**
     * Create a new service instance.
     *
     * @param AuditLogRepositoryInterface $repository
     */
    public function __construct(AuditLogRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    
    /**
     * Log a record creation event
     *
     * @param Model $model The model that was created
     * @param array $tags Optional tags for categorizing the log
     * @return AuditLog
     */
    public function logCreated(Model $model, array $tags = []): AuditLog
    {
        return $this->repository->logCreated($model, $tags);
    }
    
    /**
     * Log a record update event
     *
     * @param Model $model The model that was updated
     * @param array $oldValues The original values before update
     * @param array $tags Optional tags for categorizing the log
     * @return AuditLog
     */
    public function logUpdated(Model $model, array $oldValues, array $tags = []): AuditLog
    {
        return $this->repository->logUpdated($model, $oldValues, $tags);
    }
    
    /**
     * Log a record deletion event
     *
     * @param Model $model The model that was deleted
     * @param array $tags Optional tags for categorizing the log
     * @return AuditLog
     */
    public function logDeleted(Model $model, array $tags = []): AuditLog
    {
        return $this->repository->logDeleted($model, $tags);
    }
    
    /**
     * Log a record restoration event (from soft delete)
     *
     * @param Model $model The model that was restored
     * @param array $tags Optional tags for categorizing the log
     * @return AuditLog
     */
    public function logRestored(Model $model, array $tags = []): AuditLog
    {
        return $this->repository->logRestored($model, $tags);
    }
    
    /**
     * Log a custom event
     *
     * @param string $event The event name
     * @param Model $model The related model
     * @param array $oldValues Old values if applicable
     * @param array $newValues New values if applicable
     * @param array $tags Optional tags for categorizing the log
     * @return AuditLog
     */
    public function logCustomEvent(string $event, Model $model, array $oldValues = [], array $newValues = [], array $tags = []): AuditLog
    {
        return $this->repository->logCustomEvent($event, $model, $oldValues, $newValues, $tags);
    }
    
    /**
     * Get logs for a specific model
     *
     * @param Model $model The model to get logs for
     * @param int $perPage Number of logs per page
     * @return LengthAwarePaginator
     */
    public function getLogsForModel(Model $model, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getLogsForModel($model, $perPage);
    }
    
    /**
     * Get all logs with pagination and optional filtering
     *
     * @param int $perPage Number of logs per page
     * @param array $filters Optional filters to apply
     * @return LengthAwarePaginator
     */
    public function getAllLogs(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        return $this->repository->getAllLogs($perPage, $filters);
    }
    
    /**
     * Get logs created by a specific user
     *
     * @param int $userId The user ID
     * @param int $perPage Number of logs per page
     * @return LengthAwarePaginator
     */
    public function getLogsByUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getLogsByUser($userId, $perPage);
    }
    
    /**
     * Get logs by event type
     *
     * @param string $event The event type to filter by
     * @param int $perPage Number of logs per page
     * @return LengthAwarePaginator
     */
    public function getLogsByEvent(string $event, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getLogsByEvent($event, $perPage);
    }
    
    /**
     * Get logs with specific tags
     *
     * @param array $tags Tags to filter by
     * @param int $perPage Number of logs per page
     * @return LengthAwarePaginator
     */
    public function getLogsByTags(array $tags, int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->getLogsByTags($tags, $perPage);
    }
} 