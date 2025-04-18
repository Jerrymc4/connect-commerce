@extends('layouts.admin.dashboard')

@section('content')
<div class="container px-4 mx-auto">
    <script>
        // Redirect to the settings page with the categories tab active
        window.location.href = "{{ route('admin.settings', ['tab' => 'categories']) }}";
    </script>
    
    <div class="text-center py-12">
        <div class="flex justify-center items-center flex-col">
            <div class="w-16 h-16 relative">
                <div class="absolute inset-0 rounded-full border-4 border-gray-200 dark:border-gray-700"></div>
                <div class="absolute inset-0 rounded-full border-4 border-t-accent animate-spin"></div>
            </div>
            <p class="mt-4 text-lg text-primary">Redirecting to categories...</p>
            <p class="mt-2 text-secondary">If you're not redirected automatically, <a href="{{ route('admin.settings', ['tab' => 'categories']) }}" class="text-accent hover:underline">click here</a></p>
        </div>
    </div>
</div>
@endsection 