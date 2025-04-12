<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    protected AuditLogService $auditLogService;
    
    /**
     * Create a new controller instance.
     *
     * @param AuditLogService $auditLogService
     */
    public function __construct(AuditLogService $auditLogService)
    {
        $this->auditLogService = $auditLogService;
    }
    
    /**
     * Display a listing of audit logs with filtering options.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $filters = $this->getFiltersFromRequest($request);
        $perPage = $request->input('per_page', 15);
        
        $logs = $this->auditLogService->getAllLogs($perPage, $filters);
        
        // Get unique event types and model types for filter dropdowns
        $eventTypes = AuditLog::select('event')->distinct()->pluck('event');
        $modelTypes = AuditLog::select('auditable_type')->distinct()->pluck('auditable_type')
            ->map(function($type) {
                $parts = explode('\\', $type);
                return end($parts);
            });
        
        return view('store.audit-logs.index', compact('logs', 'filters', 'eventTypes', 'modelTypes'));
    }
    
    /**
     * Display details for a specific audit log.
     *
     * @param int $id
     * @return View
     */
    public function show(int $id): View
    {
        $log = AuditLog::findOrFail($id);
        
        return view('store.audit-logs.show', compact('log'));
    }
    
    /**
     * Export audit logs (optional feature).
     *
     * @param Request $request
     * @return mixed
     */
    public function export(Request $request)
    {
        $filters = $this->getFiltersFromRequest($request);
        
        // Implementation will depend on your preferred export format (CSV, Excel, etc.)
        // For now, we'll redirect back with a message
        return redirect()->route('store.audit-logs.index')
            ->with('info', 'Export functionality will be implemented in a future update.');
    }
    
    /**
     * Helper method to extract filters from the request.
     *
     * @param Request $request
     * @return array
     */
    protected function getFiltersFromRequest(Request $request): array
    {
        $filters = [];
        
        if ($request->filled('event')) {
            $filters['event'] = $request->input('event');
        }
        
        if ($request->filled('model_type')) {
            $modelType = $request->input('model_type');
            $filters['auditable_type'] = "App\\Models\\$modelType";
        }
        
        if ($request->filled('user_id')) {
            $filters['user_id'] = $request->input('user_id');
        }
        
        if ($request->filled('date_from')) {
            $filters['date_from'] = $request->input('date_from');
        }
        
        if ($request->filled('date_to')) {
            $filters['date_to'] = $request->input('date_to');
        }
        
        return $filters;
    }
}
