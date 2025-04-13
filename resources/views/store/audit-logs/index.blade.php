@extends('layouts.admin.dashboard')

@section('content')
<div class="container px-3 mx-auto">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Audit Logs</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Track changes and actions in your store</p>
        </div>
        
        <div>
            <a href="{{ route('admin.audit-logs.export', [], false) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors inline-flex items-center">
                <i class="fas fa-download mr-2"></i>
                Export Logs
            </a>
        </div>
    </div>
    
    <!-- Flash Messages -->
    @if(session('success'))
    <div id="success-alert" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <div class="flex justify-between items-center">
            <span>{{ session('success') }}</span>
            <button type="button" class="ml-4 text-green-700 hover:text-green-900 focus:outline-none" onclick="closeAlert('success-alert')">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif
    
    @if(session('error'))
    <div id="error-alert" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
        <div class="flex justify-between items-center">
            <span>{{ session('error') }}</span>
            <button type="button" class="ml-4 text-red-700 hover:text-red-900 focus:outline-none" onclick="closeAlert('error-alert')">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif

    @if(session('info'))
    <div id="info-alert" class="mb-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative" role="alert">
        <div class="flex justify-between items-center">
            <span>{{ session('info') }}</span>
            <button type="button" class="ml-4 text-blue-700 hover:text-blue-900 focus:outline-none" onclick="closeAlert('info-alert')">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif
    
    <!-- Filters -->
    <div class="mb-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700">
        <form action="{{ route('admin.audit-logs.index', [], false) }}" method="GET" class="space-y-4">
            <div class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-2">Filter Logs</div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Event Type Filter -->
                <div>
                    <label for="event" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Event Type</label>
                    <select name="event" id="event" class="w-full rounded-md border border-gray-300 dark:border-gray-600 px-3 py-2 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Events</option>
                        @foreach($eventTypes as $eventType)
                            <option value="{{ $eventType }}" {{ isset($filters['event']) && $filters['event'] == $eventType ? 'selected' : '' }}>
                                {{ ucfirst($eventType) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Model Type Filter -->
                <div>
                    <label for="model_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Record Type</label>
                    <select name="model_type" id="model_type" class="w-full rounded-md border border-gray-300 dark:border-gray-600 px-3 py-2 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Types</option>
                        @foreach($modelTypes as $modelType)
                            <option value="{{ $modelType }}" {{ isset($filters['auditable_type']) && $filters['auditable_type'] == "App\\Models\\$modelType" ? 'selected' : '' }}>
                                {{ $modelType }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Date Range Filter -->
                <div>
                    <label for="date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date Range</label>
                    <div class="flex space-x-2">
                        <input type="date" name="date_from" id="date_from" value="{{ $filters['date_from'] ?? '' }}" 
                            class="w-full rounded-md border border-gray-300 dark:border-gray-600 px-3 py-2 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="From">
                        <input type="date" name="date_to" id="date_to" value="{{ $filters['date_to'] ?? '' }}" 
                            class="w-full rounded-md border border-gray-300 dark:border-gray-600 px-3 py-2 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="To">
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end">
                <a href="{{ route('admin.audit-logs.index', [], false) }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 mr-2 transition-colors">
                    Reset
                </a>
                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition-colors">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>
    
    <!-- Logs Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Date/Time
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            User
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Event
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Record Type
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Record ID
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $log->created_at->format('M d, Y g:i A') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            @if($log->user)
                                {{ $log->user->name ?? 'User #' . $log->user_id }}
                            @else
                                System
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($log->event == 'created') bg-green-100 text-green-800 @endif
                                @if($log->event == 'updated') bg-blue-100 text-blue-800 @endif
                                @if($log->event == 'deleted') bg-red-100 text-red-800 @endif
                                @if($log->event == 'restored') bg-yellow-100 text-yellow-800 @endif
                                @if(!in_array($log->event, ['created', 'updated', 'deleted', 'restored'])) bg-gray-100 text-gray-800 @endif
                            ">
                                {{ $log->event_description }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $log->auditable_type_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ $log->auditable_id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right space-x-2">
                            <a href="{{ route('admin.audit-logs.show', $log->id, false) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                <i class="fas fa-eye"></i> View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                            <div class="flex flex-col items-center justify-center">
                                <i class="fas fa-history text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                                <p>No audit logs found</p>
                                @if(!empty($filters))
                                    <p class="mt-1 text-xs">Try adjusting your filters</p>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($logs->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
            {{ $logs->appends(request()->query())->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Close alert function
    function closeAlert(id) {
        document.getElementById(id).style.display = 'none';
    }
    
    // Auto-dismiss alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        ['success-alert', 'error-alert', 'info-alert'].forEach(function(id) {
            const alert = document.getElementById(id);
            if (alert) {
                setTimeout(function() {
                    alert.style.opacity = '0';
                    alert.style.transition = 'opacity 1s';
                    setTimeout(function() {
                        alert.style.display = 'none';
                    }, 1000);
                }, 5000);
            }
        });
    });
</script>
@endpush 