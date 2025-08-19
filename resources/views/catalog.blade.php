@extends('layouts.app')

@section('title', 'كتالوج المنتجات - بحث متقدم')

@section('content')
<div class="container-fluid mt-4" style="direction: rtl; text-align: right;">
    <!-- Search Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="GET" action="{{ route('catalog') }}" id="searchForm">
                        <div class="row align-items-center">
                            <div class="col-md-5">
                                <label class="form-label fw-bold">🔍 البحث في المنتجات</label>
                                <div class="input-group">
                                    <input type="text" class="form-control form-control-lg" name="search" 
                                           value="{{ request('search') }}" placeholder="ابحث بالاسم, الكود, الموديل..." 
                                           id="searchInput" autocomplete="off">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                <div id="searchSuggestions" class="position-absolute w-100 bg-white border rounded shadow-sm" style="z-index: 1000; display: none;"></div>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">📊 ترتيب حسب</label>
                                <select class="form-select form-select-lg" name="sort" onchange="this.form.submit()">
                                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>الاسم</option>
                                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>السعر: من الأقل للأعلى</option>
                                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>السعر: من الأعلى للأقل</option>
                                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>الأحدث</option>
                                </select>
                            </div>
                            <div class="col-md-4 d-flex align-items-end justify-content-end">
                                <a href="{{ route('catalog') }}" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-undo"></i> مسح الفلاتر
                                </a>
                                <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-cog"></i> إدارة المنتجات
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-filter me-2"></i>فلاتر البحث</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('catalog') }}" id="filterForm">
                        <input type="hidden" name="search" value="{{ request('search') }}">
                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                        
                        <!-- Price Range -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-primary">💰 نطاق السعر</h6>
                            <div class="row">
                                <div class="col-6">
                                    <input type="number" class="form-control form-control-sm" name="min_price" 
                                           placeholder="من" value="{{ request('min_price') }}" step="0.01">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control form-control-sm" name="max_price" 
                                           placeholder="إلى" value="{{ request('max_price') }}" step="0.01">
                                </div>
                            </div>
                            @if(isset($priceRange))
                            <small class="text-muted">النطاق: {{ number_format($priceRange->min_price, 2) }} - {{ number_format($priceRange->max_price, 2) }} جنيه</small>
                            @endif
                        </div>
                        
                        <!-- Categories -->
                        <div class="mb-4">
                            <h6 class="fw-bold text-primary">📱 الفئات</h6>
                            @if(isset($categories))
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="category" value="" id="all" 
                                       {{ !request('category') ? 'checked' : '' }} onchange="this.form.submit()">
                                <label class="form-check-label" for="all">جميع المنتجات</label>
                            </div>
                            @foreach(['electronics' => 'إلكترونيات', 'appliances' => 'أجهزة منزلية', 'furniture' => 'أثاث'] as $key => $value)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="category" value="{{ $key }}" id="{{ $key }}" 
                                       {{ request('category') == $key ? 'checked' : '' }} onchange="this.form.submit()">
                                <label class="form-check-label" for="{{ $key }}">
                                    {{ $value }} <span class="badge bg-secondary rounded-pill">{{ $categories[$key] ?? 0 }}</span>
                                </label>
                            </div>
                            @endforeach
                            @endif
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search"></i> تطبيق الفلاتر
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Products Grid -->
        <div class="col-lg-9">
            <!-- Server Messages -->
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Results Info & User Credit -->
            @if(isset($products))
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="text-primary mb-0">📦 المنتجات ({{ $products->total() }} منتج)</h4>
                @auth
                    <div class="user-credit-display alert alert-success py-2 px-3 mb-0">
                        رصيدك: <strong id="user-credit">{{ number_format(Auth::user()->credit, 2) }}</strong> جنيه
                    </div>
                @endauth
            </div>
            @endif
            
            <!-- Products -->
            <div class="row" id="productsContainer">
                @forelse($products as $product)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 shadow-sm product-card" style="transition: transform 0.2s;">
                        <div class="position-relative">
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://via.placeholder.com/300x200' }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $product->name }}">
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-dark">{{ $product->code }}</span>
                            </div>
                             <div class="position-absolute top-0 start-0 m-2">
                                <span class="badge bg-info">المتبقي: {{ $product->stock }}</span>
                            </div>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title text-truncate" title="{{ $product->name }}">{{ $product->name }}</h5>
                            <p class="text-muted small mb-2">{{ $product->model }}</p>
                            <p class="card-text text-muted small flex-grow-1">{{ Str::limit($product->description, 70) }}</p>
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="h5 text-success mb-0">{{ number_format($product->price, 2) }} جنيه</span>
                                </div>
                                <div class="d-grid gap-2">
                                    @auth
                                        <form action="{{ route('catalog.purchase') }}" method="POST" class="d-grid">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                                            <button type="submit" class="btn btn-primary w-100">
                                                <i class="fas fa-shopping-cart me-1"></i> شراء الآن
                                            </button>
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-primary">
                                            <i class="fas fa-sign-in-alt me-1"></i>
                                            تسجيل الدخول للشراء
                                        </a>
                                    @endauth
                                    <button class="btn btn-outline-secondary btn-sm view-details" 
                                            data-id="{{ $product->id }}" data-bs-toggle="modal" data-bs-target="#productModal">
                                        <i class="fas fa-eye me-1"></i>عرض التفاصيل
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-search fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">لا توجد منتجات تطابق بحثك</h4>
                        <p class="text-muted">جرب تغيير معايير البحث أو الفلاتر.</p>
                        <a href="{{ route('catalog') }}" class="btn btn-primary">
                            <i class="fas fa-undo"></i> عرض جميع المنتجات
                        </a>
                    </div>
                </div>
                @endforelse
            </div>
            
            <!-- Pagination -->
            @if($products->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $products->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    </div>

</div>

<!-- Product Details Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="productModalLabel">تفاصيل المنتج</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="productModalBody">
                <!-- AJAX content will be loaded here -->
            </div>
            <div class="modal-footer">
                <form id="purchase-form" action="{{ route('catalog.purchase') }}" method="POST" class="w-100">
                    @csrf
                    <input type="hidden" id="product-id-input" name="product_id" value="">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
                    <button type="submit" id="purchase-button" class="btn btn-primary">شراء الآن</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Event delegation for view details button
    document.body.addEventListener('click', function(e) {
        const viewDetailsBtn = e.target.closest('.view-details');
        if (viewDetailsBtn) {
            handleViewDetails(viewDetailsBtn);
        }
    });

    function handleViewDetails(button) {
        const productId = button.dataset.id;
        const modalBody = document.getElementById('productModalBody');
        modalBody.innerHTML = '<div class="text-center p-5"><i class="fas fa-spinner fa-spin fa-3x"></i><p class="mt-2">جاري التحميل...</p></div>';

        fetch(`/catalog/product/${productId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const product = data.product;
                    modalBody.innerHTML = `
                        <div class="row">
                            <div class="col-md-5">
                                <img src="${product.image ? `{{ asset('storage') }}/${product.image}` : 'https://via.placeholder.com/400'}" class="img-fluid rounded shadow-sm" alt="${product.name}">
                            </div>
                            <div class="col-md-7">
                                <h3>${product.name}</h3>
                                <p class="text-muted"><strong>الموديل:</strong> ${product.model} | <strong>الكود:</strong> ${product.code}</p>
                                <h4 class="text-success fw-bold">${parseFloat(product.price).toFixed(2)} جنيه</h4>
                                <hr>
                                <h5>الوصف:</h5>
                                <p>${product.description || 'لا يوجد وصف متاح.'}</p>
                                <p class="small text-muted mt-3">تاريخ الإضافة: ${new Date(product.created_at).toLocaleDateString()}</p>
                            </div>
                        </div>
                    `;
                    document.getElementById('product-id-input').value = product.id;
                } else {
                    modalBody.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                }
            })
            .catch(error => {
                console.error('View Details Error:', error);
                modalBody.innerHTML = `<div class="alert alert-danger">حدث خطأ أثناء تحميل تفاصيل المنتج.</div>`;
            });
    }

    // Search suggestions
    const searchInput = document.getElementById('searchInput');
    const suggestionsContainer = document.getElementById('searchSuggestions');
    let debounceTimer;

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            clearTimeout(debounceTimer);
            const query = this.value;

            if (query.length < 2) {
                suggestionsContainer.style.display = 'none';
                return;
            }

            debounceTimer = setTimeout(() => {
                fetch(`{{ route('catalog.suggestions') }}?query=${query}`)
                    .then(response => response.json())
                    .then(data => {
                        suggestionsContainer.innerHTML = '';
                        if (data.length > 0) {
                            const list = document.createElement('ul');
                            list.className = 'list-group';
                            data.forEach(item => {
                                const li = document.createElement('li');
                                li.className = 'list-group-item list-group-item-action';
                                li.style.cursor = 'pointer';
                                li.textContent = item.name;
                                li.addEventListener('click', () => {
                                    searchInput.value = item.name;
                                    suggestionsContainer.style.display = 'none';
                                    document.getElementById('searchForm').submit();
                                });
                                list.appendChild(li);
                            });
                            suggestionsContainer.appendChild(list);
                            suggestionsContainer.style.display = 'block';
                        } else {
                            suggestionsContainer.style.display = 'none';
                        }
                    });
            }, 300);
        });

        document.addEventListener('click', function (e) {
            if (suggestionsContainer && !suggestionsContainer.contains(e.target) && e.target !== searchInput) {
                suggestionsContainer.style.display = 'none';
            }
        });
    }
});
</script>
@endpush