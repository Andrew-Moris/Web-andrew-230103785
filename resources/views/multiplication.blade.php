@php($j = 5)

@extends('layouts.app')

@section('title', 'Multiplication Table - Web Application')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="fas fa-times me-2"></i>Multiplication Table</h1>
                <div>
                    <a href="{{ route('even-numbers') }}" class="btn btn-outline-primary me-2">
                        <i class="fas fa-sort-numeric-up me-1"></i>Even Numbers
                    </a>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-calculator me-2"></i>{{$j}} Multiplication Table</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>Number</th>
                                            <th>× {{$j}}</th>
                                            <th>= Result</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (range(1, 10) as $i)
                                            <tr>
                                                <td class="text-center">{{$i}}</td>
                                                <td class="text-center">× {{$j}}</td>
                                                <td class="text-center fw-bold text-primary">{{ $i * $j }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Table Statistics</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <div class="text-center mb-3">
                                        <h4 class="text-primary">{{ array_sum(range(1, 10)) * $j }}</h4>
                                        <small class="text-muted">Sum of Results</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center mb-3">
                                        <h4 class="text-success">{{ (array_sum(range(1, 10)) * $j) / 10 }}</h4>
                                        <small class="text-muted">Average Result</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <h6><i class="fas fa-list me-2"></i>All Results:</h6>
                                <div class="d-flex flex-wrap">
                                    @foreach (range(1, 10) as $i)
                                        <span class="badge bg-info me-1 mb-1">{{ $i * $j }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Additional Tables -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><i class="fas fa-table me-2"></i>Quick Reference Tables</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach([2, 3, 4, 6, 7, 8, 9] as $tableNum)
                                    <div class="col-md-3 mb-3">
                                        <div class="card border-primary">
                                            <div class="card-header bg-primary text-white">
                                                <h6 class="mb-0">{{$tableNum}} × Table</h6>
                                            </div>
                                            <div class="card-body p-2">
                                                @foreach (range(1, 5) as $i)
                                                    <div class="small">{{$i}} × {{$tableNum}} = {{ $i * $tableNum }}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
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