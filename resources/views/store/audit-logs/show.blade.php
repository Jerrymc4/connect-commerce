@extends('layouts.store')

@section('content')
<div class="container px-3 mx-auto">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Audit Log Details</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">Viewing detailed information for a specific log entry</p>
        </div>
        
        <div>
            <a href="{{ route('store.audit-logs.index', [], false) }}" class="px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700 inline-flex items-center transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Logs
            </a>
        </div>
    </div>
    
    <!-- Log Details -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
            <h2 class="text-lg font-medium text-gray-800 dark:text-gray-200">Event Information</h2>
        </div>
        
        <div class="px-6 py-4">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Event ID</dt>
                    <dd class="mt-1 text-sm text-gray-800 dark:text-gray-200">{{ $log->id }}</dd>
                </div>
                
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Timestamp</dt>
                    <dd class="mt-1 text-sm text-gray-800 dark:text-gray-200">{{ $log->created_at->format('F d, Y g:i:s A') }}</dd>
                </div>
                
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Event Type</dt>
                    <dd class="mt-1 text-sm">
                        <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($log->event == 'created') bg-green-100 text-green-800 @endif
                            @if($log->event == 'updated') bg-blue-100 text-blue-800 @endif
                            @if($log->event == 'deleted') bg-red-100 text-red-800 @endif
                            @if($log->event == 'restored') bg-yellow-100 text-yellow-800 @endif
                            @if(!in_array($log->event, ['created', 'updated', 'deleted', 'restored'])) bg-gray-100 text-gray-800 @endif
                        ">
                            {{ $log->event_description }}
                        </span>
                    </dd>
                </div>
                
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">IP Address</dt>
                    <dd class="mt-1 text-sm text-gray-800 dark:text-gray-200">{{ $log->ip_address ?? 'Not recorded' }}</dd>
                </div>
                
                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">URL</dt>
                    <dd class="mt-1 text-sm text-gray-800 dark:text-gray-200 break-all">{{ $log->url ?? 'Not recorded' }}</dd>
                </div>
                
                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">User Agent</dt>
                    <dd class="mt-1 text-sm text-gray-800 dark:text-gray-200 break-all">{{ $log->user_agent ?? 'Not recorded' }}</dd>
                </div>
            </dl>
        </div>
    </div>
    
    <!-- User Information -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
            <h2 class="text-lg font-medium text-gray-800 dark:text-gray-200">User Information</h2>
        </div>
        
        <div class="px-6 py-4">
            @if($log->user)
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">User ID</dt>
                        <dd class="mt-1 text-sm text-gray-800 dark:text-gray-200">{{ $log->user_id }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">User Type</dt>
                        <dd class="mt-1 text-sm text-gray-800 dark:text-gray-200">{{ $log->user_type ? class_basename($log->user_type) : 'Unknown' }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
                        <dd class="mt-1 text-sm text-gray-800 dark:text-gray-200">{{ $log->user->name ?? 'Not available' }}</dd>
                    </div>
                    
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                        <dd class="mt-1 text-sm text-gray-800 dark:text-gray-200">{{ $log->user->email ?? 'Not available' }}</dd>
                    </div>
                </dl>
            @else
                <div class="flex items-center justify-center py-4">
                    <p class="text-gray-500 dark:text-gray-400">System action or user not recorded</p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Record Information -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700 mb-6">
        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
            <h2 class="text-lg font-medium text-gray-800 dark:text-gray-200">Record Information</h2>
        </div>
        
        <div class="px-6 py-4">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Record Type</dt>
                    <dd class="mt-1 text-sm text-gray-800 dark:text-gray-200">{{ $log->auditable_type_name }}</dd>
                </div>
                
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Record ID</dt>
                    <dd class="mt-1 text-sm text-gray-800 dark:text-gray-200">{{ $log->auditable_id }}</dd>
                </div>
                
                @if(!empty($log->tags))
                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tags</dt>
                    <dd class="mt-1 flex flex-wrap gap-2">
                        @foreach($log->tags as $tag)
                            <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md text-xs">
                                {{ $tag }}
                            </span>
                        @endforeach
                    </dd>
                </div>
                @endif
            </dl>
        </div>
    </div>
    
    <!-- Value Changes -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="border-b border-gray-200 dark:border-gray-700 px-6 py-4">
            <h2 class="text-lg font-medium text-gray-800 dark:text-gray-200">Changed Values</h2>
        </div>
        
        <div class="px-6 py-4">
            @if(!empty($log->old_values) || !empty($log->new_values))
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-700">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Field</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Old Value</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">New Value</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @if($log->event === 'created')
                                @foreach($log->new_values as $field => $value)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700 dark:text-gray-300">{{ $field }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">-</td>
                                    <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200 break-all">
                                        @if(is_array($value) || is_object($value))
                                            <pre class="text-xs bg-gray-50 dark:bg-gray-900 p-2 rounded overflow-auto max-h-40">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                        @else
                                            {{ $value ?? 'null' }}
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            @elseif($log->event === 'deleted')
                                @foreach($log->old_values as $field => $value)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700 dark:text-gray-300">{{ $field }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200 break-all">
                                        @if(is_array($value) || is_object($value))
                                            <pre class="text-xs bg-gray-50 dark:bg-gray-900 p-2 rounded overflow-auto max-h-40">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                        @else
                                            {{ $value ?? 'null' }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">-</td>
                                </tr>
                                @endforeach
                            @else
                                @php
                                    // Determine all fields that have changes
                                    $allFields = array_unique(array_merge(
                                        array_keys($log->old_values ?? []), 
                                        array_keys($log->new_values ?? [])
                                    ));
                                    sort($allFields);
                                @endphp
                                
                                @foreach($allFields as $field)
                                    @php
                                        $oldValue = $log->old_values[$field] ?? null;
                                        $newValue = $log->new_values[$field] ?? null;
                                        $hasChanged = $oldValue != $newValue;
                                    @endphp
                                    <tr class="{{ $hasChanged ? 'bg-yellow-50 dark:bg-yellow-900/10' : '' }}">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700 dark:text-gray-300">{{ $field }}</td>
                                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200 break-all">
                                            @if(isset($log->old_values[$field]))
                                                @if(is_array($oldValue) || is_object($oldValue))
                                                    <pre class="text-xs bg-gray-50 dark:bg-gray-900 p-2 rounded overflow-auto max-h-40">{{ json_encode($oldValue, JSON_PRETTY_PRINT) }}</pre>
                                                @else
                                                    {{ $oldValue ?? 'null' }}
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-800 dark:text-gray-200 break-all">
                                            @if(isset($log->new_values[$field]))
                                                @if(is_array($newValue) || is_object($newValue))
                                                    <pre class="text-xs bg-gray-50 dark:bg-gray-900 p-2 rounded overflow-auto max-h-40">{{ json_encode($newValue, JSON_PRETTY_PRINT) }}</pre>
                                                @else
                                                    {{ $newValue ?? 'null' }}
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            @else
                <div class="flex items-center justify-center py-6">
                    <p class="text-gray-500 dark:text-gray-400">No value changes recorded for this event</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 