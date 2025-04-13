@extends('layouts.store')

@section('content')
<div class="container px-4 mx-auto">
    <script>
        // Redirect to the settings page with the categories tab active
        window.location.href = "{{ route('store.settings', ['tab' => 'categories']) }}";
    </script>
    
    <div class="text-center py-12">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Redirecting...</span>
        </div>
        <p class="mt-2">Redirecting to categories...</p>
    </div>
</div>
@endsection 