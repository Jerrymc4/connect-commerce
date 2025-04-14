@extends('layouts.admin.dashboard')

@section('content')
<div class="container px-3 mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Audit Log Details</h1>
        <p class="text-gray-600 mt-1">Viewing log #{{ $log->id }}</p>
    </div>
    
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('admin.audit-logs.index', [], false) }}" class="inline-flex items-center text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-2"></i> Back to Audit Logs
        </a>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Event Information -->
        <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Event Information</h2>
            
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-3">
                <dt class="text-sm font-medium text-gray-500">Event ID</dt>
                <dd class="text-sm text-gray-900">{{ $log->id }}</dd>
                
                <dt class="text-sm font-medium text-gray-500">Timestamp</dt>
                <dd class="text-sm text-gray-900">{{ $log->created_at->format('M d, Y g:i:s A') }}</dd>
                
                <dt class="text-sm font-medium text-gray-500">Event Type</dt>
                <dd class="text-sm">
                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                        @if($log->event == 'created') bg-blue-100 text-blue-800 @endif
                        @if($log->event == 'updated') bg-blue-100 text-blue-800 @endif
                        @if($log->event == 'deleted') bg-gray-100 text-gray-800 @endif
                        @if($log->event == 'restored') bg-gray-100 text-gray-800 @endif
                        @if(!in_array($log->event, ['created', 'updated', 'deleted', 'restored'])) bg-gray-100 text-gray-800 @endif
                    ">
                        {{ $log->event_description }}
                    </span>
                </dd>
                
                <dt class="text-sm font-medium text-gray-500">IP Address</dt>
                <dd class="text-sm text-gray-900">{{ $log->ip_address ?? 'N/A' }}</dd>
                
                <dt class="text-sm font-medium text-gray-500">URL</dt>
                <dd class="text-sm text-gray-900 truncate">{{ $log->url ?? 'N/A' }}</dd>
                
                <dt class="text-sm font-medium text-gray-500">User Agent</dt>
                <dd class="text-sm text-gray-900 truncate">{{ $log->user_agent ?? 'N/A' }}</dd>
            </dl>
        </div>
        
        <!-- User Information -->
        <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">User Information</h2>
            
            @if($log->user)
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-3">
                    <dt class="text-sm font-medium text-gray-500">User ID</dt>
                    <dd class="text-sm text-gray-900">{{ $log->user_id }}</dd>
                    
                    <dt class="text-sm font-medium text-gray-500">User Type</dt>
                    <dd class="text-sm text-gray-900">{{ $log->user_type_name }}</dd>
                    
                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                    <dd class="text-sm text-gray-900">{{ $log->user->name }}</dd>
                    
                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                    <dd class="text-sm text-gray-900">{{ $log->user->email }}</dd>
                </dl>
            @else
                <div class="py-8 flex justify-center items-center">
                    <p class="text-gray-500">System Event</p>
                </div>
            @endif
        </div>
        
        <!-- Record Information -->
        <div class="bg-white rounded-lg shadow-lg p-6 border border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Record Information</h2>
            
            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-3">
                <dt class="text-sm font-medium text-gray-500">Record Type</dt>
                <dd class="text-sm text-gray-900">{{ $log->auditable_type_name }}</dd>
                
                <dt class="text-sm font-medium text-gray-500">Record ID</dt>
                <dd class="text-sm text-gray-900">{{ $log->auditable_id }}</dd>
                
                @if($log->tags && count($log->tags) > 0)
                    <dt class="text-sm font-medium text-gray-500 col-span-2 mt-2">Tags</dt>
                    <dd class="text-sm text-gray-900 col-span-2 flex flex-wrap gap-2">
                        @foreach($log->tags as $tag)
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded-full text-xs">{{ $tag }}</span>
                        @endforeach
                    </dd>
                @endif
            </dl>
        </div>
    </div>
    
    <!-- Changed Values -->
    <div class="mt-6 bg-white rounded-lg shadow-lg p-6 border border-gray-200">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Changed Values</h2>
        
        @if($log->event == 'created')
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Field</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($log->new_values as $field => $value)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $field }}</td>
                                <td class="px-6 py-4 whitespace-pre-wrap text-sm text-gray-500">
                                    @if(is_array($value) || is_object($value))
                                        <pre class="text-xs bg-gray-100 p-2 rounded-md overflow-x-auto">{{ json_encode($value, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                    @elseif(is_null($value))
                                        <span class="text-gray-400">null</span>
                                    @elseif($value === '')
                                        <span class="text-gray-400">(empty string)</span>
                                    @else
                                        {{ $value }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @elseif($log->event == 'deleted')
            <div class="py-8 flex justify-center items-center">
                <p class="text-gray-500">Record was deleted. No values to display.</p>
            </div>
        @elseif(count($log->old_values) > 0 || count($log->new_values) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Field</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Old Value</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">New Value</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($log->getModifiedFields() as $field)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $field }}</td>
                                <td class="px-6 py-4 whitespace-pre-wrap text-sm text-gray-500">
                                    @if(!isset($log->old_values[$field]))
                                        <span class="text-gray-400">null</span>
                                    @elseif(is_array($log->old_values[$field]) || is_object($log->old_values[$field]))
                                        <pre class="text-xs bg-gray-100 p-2 rounded-md overflow-x-auto">{{ json_encode($log->old_values[$field], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                    @elseif(is_null($log->old_values[$field]))
                                        <span class="text-gray-400">null</span>
                                    @elseif($log->old_values[$field] === '')
                                        <span class="text-gray-400">(empty string)</span>
                                    @else
                                        {{ $log->old_values[$field] }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-pre-wrap text-sm text-gray-500">
                                    @if(!isset($log->new_values[$field]))
                                        <span class="text-gray-400">null</span>
                                    @elseif(is_array($log->new_values[$field]) || is_object($log->new_values[$field]))
                                        <pre class="text-xs bg-gray-100 p-2 rounded-md overflow-x-auto">{{ json_encode($log->new_values[$field], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                    @elseif(is_null($log->new_values[$field]))
                                        <span class="text-gray-400">null</span>
                                    @elseif($log->new_values[$field] === '')
                                        <span class="text-gray-400">(empty string)</span>
                                    @else
                                        {{ $log->new_values[$field] }}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="py-8 flex justify-center items-center">
                <p class="text-gray-500">No changes were recorded for this event.</p>
            </div>
        @endif
    </div>
</div>
@endsection 