<div class="card" style="direction: rtl; text-align: right;">
    <div class="card-header">
        <h4>تصفية البحث</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('catalog') }}" method="GET">
            <!-- Search -->
            <div class="mb-3">
                <label for="search" class="form-label">بحث بالكلمة</label>
                <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}" placeholder="ابحث عن منتج...">
            </div>

            <!-- Sorting -->
            <div class="mb-3">
                <label for="sort" class="form-label">ترتيب حسب</label>
                <select name="sort" id="sort" class="form-select">
                    <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>الاسم</option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>السعر: من الأقل للأعلى</option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>السعر: من الأعلى للأقل</option>
                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>الأحدث أولاً</option>
                </select>
            </div>

            <!-- Price Range -->
            <div class="mb-3">
                <label class="form-label">نطاق السعر</label>
                <div class="row">
                    <div class="col">
                        <input type="number" name="min_price" class="form-control" value="{{ request('min_price') }}" placeholder="أدنى سعر">
                    </div>
                    <div class="col">
                        <input type="number" name="max_price" class="form-control" value="{{ request('max_price') }}" placeholder="أقصى سعر">
                    </div>
                </div>
            </div>

            <!-- Categories -->
            @if(!empty($categories))
            <div class="mb-3">
                <label class="form-label">الفئات</label>
                <ul class="list-group">
                    @foreach ($categories as $category => $count)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="category" value="{{ $category }}" id="cat-{{$category}}" {{ request('category') == $category ? 'checked' : '' }}>
                                <label class="form-check-label" for="cat-{{$category}}">
                                    {{ ucfirst($category) }}
                                </label>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ $count }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">تطبيق الفلتر</button>
                <a href="{{ route('catalog') }}" class="btn btn-secondary">إعادة تعيين</a>
            </div>
        </form>
    </div>
</div>
