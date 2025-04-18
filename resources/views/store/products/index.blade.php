@extends('layouts.admin.dashboard')

@section('content')
<div class="container px-3 mx-auto">
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-primary">Products</h1>
            <p class="text-secondary mt-1">Manage your product catalog</p>
        </div>
        
        <div>
            <a href="{{ route('admin.products.create', [], false) }}" class="btn-primary">
                <i class="fas fa-plus mr-2"></i>
                Add New Product
            </a>
        </div>
    </div>
    
    <!-- Products Table -->
    <div class="card-enhanced">
        <div class="card-header">
            <h3 class="text-lg font-semibold text-primary flex items-center">
                <i class="fas fa-box text-blue-500 mr-2"></i>
                Products
            </h3>
            <div class="flex items-center space-x-2">
                <form action="{{ route('admin.products', [], false) }}" method="get" class="flex">
                    <input type="text" name="search" placeholder="Search products..." value="{{ request('search') }}" class="form-control rounded-r-none border-r-0">
                    <button type="submit" class="btn-primary rounded-l-none">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="table-enhanced">
                <thead>
                    <tr>
                        <th class="text-center">Name</th>
                        <th class="text-center">SKU</th>
                        <th class="text-center">Price</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Stock</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td class="text-center">
                            <div class="font-medium text-primary">{{ $product->name }}</div>
                        </td>
                        <td class="text-secondary text-center">{{ $product->sku }}</td>
                        <td class="font-medium text-primary text-center">${{ number_format($product->price, 2) }}</td>
                        <td class="text-center">
                            @if($product->status === 'active')
                                <span class="badge badge-success">Active</span>
                            @elseif($product->status === 'draft')
                                <span class="badge badge-primary">Draft</span>
                            @else
                                <span class="badge badge-danger">Out of Stock</span>
                            @endif
                        </td>
                        <td class="text-secondary text-center">
                            @if($product->track_inventory)
                                {{ $product->stock }} units
                            @else
                                <span class="text-muted italic">Not tracked</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="flex justify-center space-x-2">
                                <a href="{{ route('admin.products.edit', $product->id, false) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('admin.products.show', $product->id, false) }}" class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product->id, false) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-secondary">No products found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-body border-t border-border-color">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-dismiss alerts already handled in dashboard layout
</script>
@endpush 