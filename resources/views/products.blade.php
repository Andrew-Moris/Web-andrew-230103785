@extends('layouts.app')

@section('title', 'Products - Web Application')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="fas fa-box me-2"></i>Products</h1>
                <a href="{{ route('products.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add New Product
                </a>
            </div>
            
            <!-- Search and Filter Form -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-search me-2"></i>Search & Filter</h5>
                </div>
                <div class="card-body">
                    <form class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label">Search Keywords</label>
                            <input type="text" class="form-control" placeholder="Search products...">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Min Price</label>
                            <input type="number" class="form-control" placeholder="Min Price">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Max Price</label>
                            <input type="number" class="form-control" placeholder="Max Price">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Order By</label>
                            <select class="form-select">
                                <option>Name</option>
                                <option>Price</option>
                                <option>Model</option>
                                <option>Code</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Direction</label>
                            <select class="form-select">
                                <option>Ascending</option>
                                <option>Descending</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="d-grid gap-2 w-100">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-1"></i>Search
                                </button>
                                <button type="reset" class="btn btn-danger">
                                    <i class="fas fa-times me-1"></i>Reset
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Products Display -->
            @if($products->count() > 0)
                <div class="row">
                    @foreach($products as $product)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-img-top bg-light text-center p-3">
                                <img src="{{$product->image}}?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                                     class="img-fluid" alt="{{$product->name}}" 
                                     style="max-height: 200px; object-fit: contain;">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">{{$product->name}}</h5>
                                <div class="mb-2">
                                    <span class="badge bg-primary">{{$product->model}}</span>
                                    <span class="badge bg-secondary">{{$product->code}}</span>
                                </div>
                                <p class="card-text text-muted">{{ Str::limit($product->description, 100) }}</p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="h5 text-success mb-0">{{$product->price}} LE</span>
                                    <button class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-eye me-1"></i>View Details
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Navigation Links -->
                <div class="row mt-4">
                    <div class="col-12 text-center">
                        <div class="btn-group" role="group">
                            <a href="{{ route('dashboard') }}" class="btn btn-outline-info">
                                <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                            </a>
                            <a href="{{ route('catalog') }}" class="btn btn-outline-warning">
                                <i class="fas fa-book me-1"></i>Catalog
                            </a>
                            <a href="{{ route('even-numbers') }}" class="btn btn-outline-success">
                                <i class="fas fa-calculator me-1"></i>Calculators
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                        <h5>No products found in database</h5>
                        <p class="text-muted">Please run: <code>php artisan db:seed</code></p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 