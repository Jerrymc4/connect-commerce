@extends('layouts.store')

@section('title', 'Manage Product Images - ' . $product->name)

@section('breadcrumbs')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('store.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('store.products.index') }}">Products</a></li>
        <li class="breadcrumb-item"><a href="{{ route('store.products.edit', $product->id) }}">{{ $product->name }}</a></li>
        <li class="breadcrumb-item active">Images</li>
    </ol>
@endsection

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Manage Images for {{ $product->name }}</h5>
            <a href="{{ route('store.products.edit', $product->id) }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Product
            </a>
        </div>
        <div class="card-body">
            @include('partials.alerts')
            
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Upload New Image</h6>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('store.products.images.upload', $product->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="image">Image File</label>
                                    <input type="file" name="image" id="image" class="form-control-file @error('image') is-invalid @enderror" required>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Maximum file size: 5MB. Recommended dimensions: 800x800px.</small>
                                </div>
                                <div class="form-group">
                                    <label for="alt_text">Alt Text</label>
                                    <input type="text" name="alt_text" id="alt_text" class="form-control @error('alt_text') is-invalid @enderror" placeholder="Brief description of the image">
                                    @error('alt_text')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary mt-3">
                                    <i class="fas fa-upload"></i> Upload Image
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle"></i> Image Guidelines</h6>
                        <ul class="mb-0">
                            <li>Upload high-quality images with good lighting</li>
                            <li>Use consistent aspect ratios for all product images</li>
                            <li>The first uploaded image will be set as the main product image</li>
                            <li>You can drag and drop to reorder images</li>
                            <li>The main image will be displayed first in the storefront</li>
                        </ul>
                    </div>
                </div>
            </div>

            <h6 class="mb-3">Product Images</h6>
            @if($images->count() > 0)
                <div class="row" id="sortable-images">
                    @foreach($images as $image)
                        <div class="col-md-3 mb-4" data-id="{{ $image->id }}">
                            <div class="card h-100 @if($image->is_main) border-primary @endif">
                                <div class="card-img-container" style="height: 200px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                                    <img src="{{ Storage::url($image->image) }}" class="card-img-top" alt="{{ $image->alt_text }}" style="max-height: 100%; object-fit: contain;">
                                </div>
                                <div class="card-body">
                                    @if($image->is_main)
                                        <span class="badge bg-primary mb-2">Main Image</span>
                                    @endif
                                    <p class="mb-2 text-muted small">Alt: {{ $image->alt_text }}</p>
                                    <div class="btn-group btn-group-sm w-100">
                                        <button type="button" class="btn btn-outline-secondary edit-btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editImageModal" 
                                                data-id="{{ $image->id }}"
                                                data-alt="{{ $image->alt_text }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        @if(!$image->is_main)
                                            <a href="{{ route('store.products.images.main', [$product->id, $image->id]) }}" class="btn btn-outline-primary">
                                                <i class="fas fa-star"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('store.products.images.delete', [$product->id, $image->id]) }}" 
                                           class="btn btn-outline-danger delete-image"
                                           onclick="return confirm('Are you sure you want to delete this image?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> No images have been uploaded for this product.
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Edit Image Modal -->
<div class="modal fade" id="editImageModal" tabindex="-1" aria-labelledby="editImageModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editImageForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title" id="editImageModalLabel">Edit Image Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_alt_text">Alt Text</label>
                        <input type="text" name="alt_text" id="edit_alt_text" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>
    $(document).ready(function() {
        // Image reordering
        $('#sortable-images').sortable({
            items: '.col-md-3',
            cursor: 'move',
            update: function(event, ui) {
                let images = [];
                $('#sortable-images .col-md-3').each(function() {
                    images.push($(this).data('id'));
                });
                
                $.ajax({
                    url: '{{ route('store.products.images.reorder', $product->id) }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        images: images
                    },
                    success: function(response) {
                        if (response.success) {
                            // Show success notification
                            toastr.success('Images reordered successfully');
                        }
                    },
                    error: function() {
                        // Show error notification
                        toastr.error('Failed to reorder images');
                        
                        // Reset sortable to previous state
                        $('#sortable-images').sortable('cancel');
                    }
                });
            }
        });
        
        // Edit image modal
        $('.edit-btn').on('click', function() {
            const imageId = $(this).data('id');
            const altText = $(this).data('alt');
            
            $('#edit_alt_text').val(altText);
            $('#editImageForm').attr('action', 
                '{{ route('store.products.images.update', [$product->id, 'IMAGE_ID']) }}'.replace('IMAGE_ID', imageId));
        });
    });
</script>
@endsection 