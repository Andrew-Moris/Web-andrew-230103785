<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل المستخدم</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            direction: rtl;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
        }
        
        .card-header {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            border-radius: 15px 15px 0 0 !important;
            padding: 1.5rem;
        }
        
        .form-control {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #4facfe;
            box-shadow: 0 0 0 0.2rem rgba(79, 172, 254, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            border: none;
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(79, 172, 254, 0.4);
        }
        
        .btn-secondary {
            border-radius: 10px;
            padding: 12px 30px;
            font-weight: 600;
        }
        
        .alert {
            border-radius: 10px;
            border: none;
        }
        
        .form-label {
            font-weight: 600;
            color: #495057;
            margin-bottom: 8px;
        }
        
        .role-card {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .role-card:hover {
            border-color: #4facfe;
            background-color: #f8f9ff;
        }
        
        .role-card.selected {
            border-color: #4facfe;
            background-color: #f8f9ff;
        }
        
        .role-card input[type="radio"] {
            margin-left: 10px;
        }
        
        .navbar {
            background: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .navbar-brand {
            font-weight: 700;
            color: #4facfe !important;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="fas fa-tachometer-alt me-2"></i>
                لوحة التحكم
            </a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="{{ route('admin.users-list') }}">
                    <i class="fas fa-list me-1"></i>
                    قائمة المستخدمين
                </a>
                <a class="nav-link" href="{{ route('admin.create-user') }}">
                    <i class="fas fa-user-plus me-1"></i>
                    إضافة مستخدم
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center">
                        <h3 class="mb-0">
                            <i class="fas fa-user-edit me-2"></i>
                            تعديل المستخدم: {{ $user->name }}
                        </h3>
                        <p class="mb-0 mt-2 opacity-75">تعديل بيانات وصلاحيات المستخدم</p>
                    </div>
                    <div class="card-body p-4">
                        <!-- عرض الرسائل -->
                        @if(session('success'))
                            <div class="alert alert-success d-flex align-items-center">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('success') }}
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger d-flex align-items-center">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                {{ session('error') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>يرجى تصحيح الأخطاء التالية:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- نموذج تعديل المستخدم -->
                        <form method="POST" action="{{ route('admin.update-user', $user->id) }}">
                            @csrf
                            @method('PUT')
                            
                            <!-- الاسم -->
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user me-1"></i>
                                    الاسم الكامل
                                </label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $user->name) }}" 
                                       placeholder="أدخل الاسم الكامل"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- البريد الإلكتروني -->
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>
                                    البريد الإلكتروني
                                </label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email', $user->email) }}" 
                                       placeholder="أدخل البريد الإلكتروني"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- كلمة المرور (اختيارية) -->
                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-1"></i>
                                    كلمة المرور الجديدة (اتركها فارغة إذا لم ترد تغييرها)
                                </label>
                                <input type="password" 
                                       class="form-control @error('password') is-invalid @enderror" 
                                       id="password" 
                                       name="password" 
                                       placeholder="أدخل كلمة المرور الجديدة (8 أحرف على الأقل)">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- تأكيد كلمة المرور -->
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label">
                                    <i class="fas fa-lock me-1"></i>
                                    تأكيد كلمة المرور الجديدة
                                </label>
                                <input type="password" 
                                       class="form-control" 
                                       id="password_confirmation" 
                                       name="password_confirmation" 
                                       placeholder="أعد إدخال كلمة المرور الجديدة">
                            </div>

                            <!-- اختيار الدور -->
                            <div class="mb-4">
                                <label class="form-label">
                                    <i class="fas fa-user-tag me-1"></i>
                                    دور المستخدم
                                </label>
                                
                                @php
                                    $currentRole = $user->userPermissions->role ?? 'viewer';
                                @endphp
                                
                                <div class="role-card {{ $currentRole === 'admin' ? 'selected' : '' }}" onclick="selectRole('admin')">
                                    <div class="d-flex align-items-center">
                                        <input type="radio" name="role" value="admin" id="role_admin" {{ $currentRole === 'admin' ? 'checked' : '' }}>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                <i class="fas fa-crown text-warning me-2"></i>
                                                مدير (Admin)
                                            </h6>
                                            <small class="text-muted">صلاحيات كاملة: إنشاء، تعديل، حذف، تقارير، إدارة المستخدمين</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="role-card {{ $currentRole === 'editor' ? 'selected' : '' }}" onclick="selectRole('editor')">
                                    <div class="d-flex align-items-center">
                                        <input type="radio" name="role" value="editor" id="role_editor" {{ $currentRole === 'editor' ? 'checked' : '' }}>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                <i class="fas fa-edit text-primary me-2"></i>
                                                محرر (Editor)
                                            </h6>
                                            <small class="text-muted">صلاحيات محدودة: إنشاء، تعديل، تقارير</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="role-card {{ $currentRole === 'viewer' ? 'selected' : '' }}" onclick="selectRole('viewer')">
                                    <div class="d-flex align-items-center">
                                        <input type="radio" name="role" value="viewer" id="role_viewer" {{ $currentRole === 'viewer' ? 'checked' : '' }}>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                <i class="fas fa-eye text-info me-2"></i>
                                                مشاهد (Viewer)
                                            </h6>
                                            <small class="text-muted">صلاحيات القراءة فقط: عرض التقارير</small>
                                        </div>
                                    </div>
                                </div>
                                
                                @error('role')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- أزرار التحكم -->
                            <div class="d-flex gap-3">
                                <button type="submit" class="btn btn-primary flex-fill">
                                    <i class="fas fa-save me-2"></i>
                                    حفظ التعديلات
                                </button>
                                <a href="{{ route('admin.users-list') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-right me-2"></i>
                                    إلغاء
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function selectRole(role) {
            // إزالة التحديد من جميع البطاقات
            document.querySelectorAll('.role-card').forEach(card => {
                card.classList.remove('selected');
            });
            
            // تحديد البطاقة المختارة
            event.currentTarget.classList.add('selected');
            
            // تحديد الراديو بوتن
            document.getElementById('role_' + role).checked = true;
        }
        
        // إضافة تأثيرات التفاعل
        document.querySelectorAll('.role-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                if (!this.classList.contains('selected')) {
                    this.style.transform = 'translateY(-2px)';
                    this.style.boxShadow = '0 5px 15px rgba(0,0,0,0.1)';
                }
            });
            
            card.addEventListener('mouseleave', function() {
                if (!this.classList.contains('selected')) {
                    this.style.transform = 'translateY(0)';
                    this.style.boxShadow = 'none';
                }
            });
        });
    </script>
</body>
</html>
