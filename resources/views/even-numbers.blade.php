@extends('layouts.app')

@section('title', 'Even Numbers - Web Application')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="fas fa-sort-numeric-up me-2"></i>Even Numbers Calculator</h1>
                <div>
                    <a href="{{ route('multiplication') }}" class="btn btn-outline-success me-2">
                        <i class="fas fa-times me-1"></i>Multiplication Table
                    </a>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>Numbers from 1 to 100</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="text-primary"><i class="fas fa-check-circle me-1"></i>Even Numbers (Blue)</h6>
                            <div class="mb-3">
                                @foreach (range(1, 100) as $i)
                                    @if($i%2==0)
                                        <span class="badge bg-primary me-1 mb-1">{{$i}}</span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-secondary"><i class="fas fa-times-circle me-1"></i>Odd Numbers (Gray)</h6>
                            <div class="mb-3">
                                @foreach (range(1, 100) as $i)
                                    @if($i%2!=0)
                                        <span class="badge bg-secondary me-1 mb-1">{{$i}}</span>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6><i class="fas fa-info-circle me-2"></i>Statistics</h6>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <h4 class="text-primary">{{ count(range(2, 100, 2)) }}</h4>
                                                <small class="text-muted">Even Numbers</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <h4 class="text-secondary">{{ count(range(1, 99, 2)) }}</h4>
                                                <small class="text-muted">Odd Numbers</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <h4 class="text-success">{{ count(range(1, 100)) }}</h4>
                                                <small class="text-muted">Total Numbers</small>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="text-center">
                                                <h4 class="text-info">{{ array_sum(range(2, 100, 2)) }}</h4>
                                                <small class="text-muted">Sum of Even Numbers</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Navigation Links -->
            <div class="row mt-4">
                <div class="col-12 text-center">
                    <div class="btn-group" role="group">
                        <a href="{{ route('dashboard') }}" class="btn btn-outline-info">
                            <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-box me-1"></i>Products
                        </a>
                        <a href="{{ route('catalog') }}" class="btn btn-outline-warning">
                            <i class="fas fa-book me-1"></i>Catalog
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 