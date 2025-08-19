@extends('layouts.app')

@section('title', 'Welcome - Web Application')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="jumbotron bg-light p-5 rounded">
                <h1 class="display-4">Welcome to Web Application</h1>
                <p class="lead">A comprehensive web application with multiple features and functionalities.</p>
                <hr class="my-4">
                <p>Explore our various features including products, calculators, user management, and more.</p>
                <div class="row mt-4">
                    <div class="col-md-3 mb-3">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <i class="fas fa-box fa-3x text-primary mb-3"></i>
                                <h5 class="card-title">Products</h5>
                                <p class="card-text">Browse and manage product catalog</p>
                                <a href="{{ route('products.index') }}" class="btn btn-primary">View Products</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <i class="fas fa-calculator fa-3x text-success mb-3"></i>
                                <h5 class="card-title">Calculators</h5>
                                <p class="card-text">Useful calculation tools</p>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('even-numbers') }}" class="btn btn-success">Even Numbers</a>
                                    <a href="{{ route('multiplication') }}" class="btn btn-success">Multiplication</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <i class="fas fa-tachometer-alt fa-3x text-info mb-3"></i>
                                <h5 class="card-title">Dashboard</h5>
                                <p class="card-text">Analytics and overview</p>
                                <a href="{{ route('dashboard') }}" class="btn btn-info">View Dashboard</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <i class="fas fa-book fa-3x text-warning mb-3"></i>
                                <h5 class="card-title">Catalog</h5>
                                <p class="card-text">Browse our catalog</p>
                                <a href="{{ route('catalog') }}" class="btn btn-warning">View Catalog</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                @guest
                <div class="row mt-4">
                    <div class="col-12 text-center">
                        <h4>Get Started</h4>
                        <p>Create an account or login to access all features</p>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-lg me-3">Register</a>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">Login</a>
                    </div>
                </div>
                @endguest
            </div>
        </div>
    </div>
</div>
@endsection
