<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>إنشاء حساب جديد | تصميم حديث</title>
    
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Fonts: Cairo -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* Custom styles to apply the Cairo font */
        body {
            font-family: 'Cairo', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">

    <div class="flex items-center justify-center min-h-screen">
        <div class="relative flex flex-col m-6 space-y-8 bg-white shadow-2xl rounded-2xl md:flex-row md:space-y-0 max-w-6xl">
            
            <!-- Form Section -->
            <div class="flex flex-col justify-center p-8 md:p-14 md:w-1/2">
                <span class="mb-3 text-4xl font-bold">إنشاء حساب جديد</span>
                <span class="font-light text-gray-500 mb-8">
                    املأ البيانات التالية للانضمام إلى مجتمعنا
                </span>

                <div id="alert-container" class="mb-4"></div>

                <form id="registerForm" method="POST" action="{{ route('register.submit') }}" novalidate>
                    @csrf
                    
                    <!-- Name Input -->
                    <div class="py-4">
                        <label for="name" class="mb-2 text-md font-medium text-gray-700">الاسم الكامل</label>
                        <input 
                            id="name" 
                            type="text" 
                            class="w-full p-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" 
                            name="name" 
                            value="{{ old('name') }}" 
                            required 
                            autocomplete="name" 
                            autofocus 
                            placeholder="أدخل اسمك الكامل">
                        <div class="text-red-500 text-sm mt-1" id="name-error"></div>
                    </div>

                    <!-- Email Input -->
                    <div class="py-4">
                        <label for="email" class="mb-2 text-md font-medium text-gray-700">البريد الإلكتروني</label>
                        <input 
                            id="email" 
                            type="email" 
                            class="w-full p-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autocomplete="email" 
                            placeholder="example@email.com">
                        <div class="text-red-500 text-sm mt-1" id="email-error"></div>
                    </div>

                    <!-- Phone Number Input (Egyptian) -->
                    <div class="py-4">
                        <label for="phone" class="mb-2 text-md font-medium text-gray-700">رقم الهاتف المصري (اختياري)</label>
                        <input 
                            id="phone" 
                            type="tel" 
                            class="w-full p-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" 
                            name="phone" 
                            value="{{ old('phone') }}"
                            autocomplete="tel" 
                            placeholder="مثال: 01xxxxxxxxx">
                        <div class="text-red-500 text-sm mt-1" id="phone-error"></div>
                    </div>

                    <!-- Password Input -->
                    <div class="py-4">
                        <label for="password" class="mb-2 text-md font-medium text-gray-700">كلمة المرور</label>
                        <input 
                            id="password" 
                            type="password" 
                            class="w-full p-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" 
                            name="password" 
                            required 
                            autocomplete="new-password" 
                            placeholder="أدخل كلمة مرور قوية">
                        <div class="text-red-500 text-sm mt-1" id="password-error"></div>
                    </div>
                    
                    <!-- Confirm Password Input -->
                    <div class="py-4">
                        <label for="password_confirmation" class="mb-2 text-md font-medium text-gray-700">تأكيد كلمة المرور</label>
                        <input 
                            id="password_confirmation" 
                            type="password" 
                            class="w-full p-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" 
                            name="password_confirmation" 
                            required 
                            autocomplete="new-password" 
                            placeholder="أعد كتابة كلمة المرور">
                        <div class="text-red-500 text-sm mt-1" id="password_confirmation-error"></div>
                    </div>

                    <!-- Register Button -->
                    <button type="submit" id="registerBtn" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-3 rounded-lg my-6 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-4 focus:ring-indigo-300 transition-all duration-300 ease-in-out flex items-center justify-center disabled:opacity-70 disabled:cursor-not-allowed">
                        <span class="btn-text font-semibold">إنشاء الحساب</span>
                        <span class="btn-spinner hidden">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>
                    
                    <div class="text-center mt-4 text-gray-600">
                        لديك حساب بالفعل؟
                        <a href="{{ route('login') }}" class="font-semibold text-indigo-600 hover:text-indigo-700 hover:underline">سجل الدخول</a>
                    </div>
                </form>

                <!-- Social Login Options -->
                <div class="mt-8">
                    <div class="text-center text-gray-500 mb-4">أو سجل الدخول باستخدام</div>
                    <div class="flex flex-col sm:flex-row justify-center gap-4">
                        <!-- Google Button -->
                        <a href="{{ route('google.login') }}" class="flex items-center justify-center w-full py-2 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 transition-colors">
                            <svg class="w-5 h-5 ml-2" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg"><path fill="#FFC107" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12c0-6.627,5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24c0,11.045,8.955,20,20,20c11.045,0,20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"></path><path fill="#FF3D00" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"></path><path fill="#4CAF50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"></path><path fill="#1976D2" d="M43.611,20.083H42V20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571l6.19,5.238C41.38,36.14,44,30.638,44,24C44,22.659,43.862,21.35,43.611,20.083z"></path></svg>
                            Google
                        </a>
                        <!-- Facebook Button -->
                        <a href="{{ route('facebook.login') }}" class="flex items-center justify-center w-full py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878V14.89h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v7.018C18.343 21.128 22 16.991 22 12z"></path></svg>
                            Facebook
                        </a>
                        <!-- Microsoft Button -->
                        <a href="{{ route('microsoft.login') }}" class="flex items-center justify-center w-full py-2 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-400 transition-colors">
                            <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M11.4 24H0V12.6h11.4V24zM24 24H12.6V12.6H24V24zM11.4 11.4H0V0h11.4v11.4zM24 11.4H12.6V0H24v11.4z"></path></svg>
                            Microsoft
                        </a>
                    </div>
                </div>
            </div>

            <!-- Image & Branding Section -->
            <div class="relative hidden md:block md:w-1/2">
                <img src="https://images.unsplash.com/photo-1543269865-cbf427effbad?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80" 
                        alt="صورة إنشاء حساب" 
                        class="w-full h-full object-cover rounded-r-2xl"
                        onerror="this.onerror=null;this.src='https://placehold.co/400x680/8b5cf6/ffffff?text=Join+Us!';"
                >
                <!-- Overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent rounded-r-2xl"></div>
                <!-- Text on Image -->
                <div class="absolute bottom-10 right-10 text-white text-right">
                    <h2 class="text-3xl font-bold">انضم إلى مجتمعنا</h2>
                    <p class="max-w-xs mt-2 opacity-90">استفد من كافة خدماتنا وميزاتنا الحصرية المصممة لك.</p>
                </div>
            </div>

        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const registerForm = document.getElementById('registerForm');
        const submitBtn = document.getElementById('registerBtn');
        const btnText = submitBtn.querySelector('.btn-text');
        const btnSpinner = submitBtn.querySelector('.btn-spinner');
        const alertContainer = document.getElementById('alert-container');

        // Function to create and show an alert
        const showAlert = (message, type = 'danger') => {
            const alertClasses = {
                danger: 'bg-red-100 border-red-400 text-red-700',
                success: 'bg-green-100 border-green-400 text-green-700'
            };
            const icon = type === 'danger' 
                ? `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>`
                : `<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>`;

            alertContainer.innerHTML = `
                <div class="border ${alertClasses[type]} px-4 py-3 rounded-lg relative flex items-center" role="alert">
                    <span class="ml-2">${icon}</span>
                    <span class="block sm:inline">${message}</span>
                </div>
            `;
        };
        
        // Function to clear all validation errors
        const clearErrors = () => {
            document.querySelectorAll('.text-red-500').forEach(el => el.textContent = '');
            document.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500'));
            alertContainer.innerHTML = '';
        };

        // Handle form submission for email/password registration
        registerForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            clearErrors();

            // Show loading state
            submitBtn.disabled = true;
            btnText.classList.add('hidden');
            btnSpinner.classList.remove('hidden');

            const formData = new FormData(registerForm);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch(registerForm.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: formData,
                });

                const result = await response.json();

                if (!response.ok) {
                    // Handle validation errors (422) or other server errors
                    if (response.status === 422 && result.errors) {
                        Object.keys(result.errors).forEach(key => {
                            const errorKey = key.replace('.', '_'); // Adjust for password_confirmation
                            const errorEl = document.getElementById(`${errorKey}-error`);
                            const inputEl = document.getElementById(errorKey);
                            if (errorEl) {
                                errorEl.textContent = result.errors[key][0];
                            }
                            if(inputEl) {
                                inputEl.classList.add('border-red-500');
                            }
                        });
                        if(result.message) {
                            showAlert(result.message, 'danger');
                        }
                    } else {
                        showAlert(result.message || 'حدث خطأ غير متوقع.', 'danger');
                    }
                } else {
                    // Handle success
                    showAlert('تم إنشاء حسابك بنجاح! سيتم توجيهك الآن.', 'success');
                    setTimeout(() => {
                        window.location.href = result.redirect || '/';
                    }, 2000);
                }
            } catch (error) {
                console.error('Fetch Error:', error);
                showAlert('حدث خطأ في الشبكة. يرجى التحقق من اتصالك.', 'danger');
            } finally {
                // Reset button state
                submitBtn.disabled = false;
                btnText.classList.remove('hidden');
                btnSpinner.classList.add('hidden');
            }
        });


    });
    </script>
</body>
</html>

@section('title', 'إنشاء حساب جديد')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
        background: linear-gradient(135deg, #f0f4ff, #e3ebf6 );
        font-family: 'Cairo' , sans-serif;
        direction: rtl;
        min-height: 100vh;
    }
    
    .auth-container  {
        display : flex;
        min-height: 100vh ;
    }
    
    .auth-brand-side {
        flex: 1;
        background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 60px 40px;
        text-align: center;
    }
    
    .brand-content  {
        max-width: 400px;
        background: #fff ;
        border-radius: 15px ;
        padding: 30px ;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05 );
    }
    
    .brand-icon {
        font-size: 4rem;
        margin-bottom: 2rem;
        display: inline-block;
        color: white;
    }
    
    .brand-content h1 {
        font-size: 2.5rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        color: white;
    }
    
    .brand-content p {
        font-size: 1.1rem;
        margin-bottom: 2.5rem;
        opacity: 0.9;
        line-height: 1.6;
        font-weight: 400;
    }
    
    .brand-features {
        list-style: none;
        padding: 0;
        text-align: right;
    }
    
    .brand-features li  {
        font-size: 1.05rem ;
        margin-bottom: 10px ;
        display: flex;
        align-items: center;
        opacity: 0.9;
    }
    
    .brand-features i  {
        color: #007bff ;
        margin-left: 8px ;
        font-size: 1.2rem;
        width: 20px;
    }
    
    .auth-form-side {
        flex: 1;
        background: white;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 60px 40px;
    }
    
    .form-wrapper {
        width: 100%;
        max-width: 400px;
        background: #fff ;
        border-radius: 15px ;
        padding: 30px ;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05 );
    }
    
    .form-wrapper h2 {
        font-size: 1.8rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .form-wrapper .subtitle {
        margin-bottom: 2rem;
        color: #6b7280;
        font-size: 0.95rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #374151;
        font-size: 0.9rem;
    }
    
    .form-group .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        font-size: 0.95rem;
        transition: border-color 0.2s;
        background: white;
    }
    
    .form-group .form-control:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
    }
    
    .form-options {
        display: flex;
        justify-content: flex-start;
        align-items: center;
        margin-bottom: 1.5rem;
        font-size: 0.9rem;
    }
    
    .form-check {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .form-check input {
        width: 16px;
        height: 16px;
        accent-color: #6366f1;
    }
    
    .btn-submit  {
        background: linear-gradient(90deg, #007bff, #0056d2 );
        color: #fff ;
        border : none;
        padding: 12px ;
        width: 100% ;
        font-size: 1.1rem ;
        border-radius: 8px ;
        transition: transform 0.2s ease, box-shadow 0.2s  ease;
    }
    .btn-submit:hover  {
        transform: translateY(-2px );
        box-shadow: 0 4px 12px rgba(0,0,0,0.1 );
    }
    
    .divider {
        text-align: center;
        margin: 1.5rem 0;
        color: #9ca3af;
        font-size: 0.9rem;
    }
    
    .social-login {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }
    
    .btn-social {
        flex: 1;
        padding: 0.75rem;
        border-radius: 8px;
        border: 1px solid #d1d5db;
        background: white;
        color: #374151;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-social:hover {
        background: #f9fafb;
        border-color: #9ca3af;
    }
    
    .btn-google {
        color: #ea4335;
        border-color: #ea4335;
    }
    
    .btn-google:hover {
        background: #ea4335;
        color: white;
    }
    
    .btn-facebook {
        color: #1877f2;
        border-color: #1877f2;
    }
    
    .btn-facebook:hover {
        background: #1877f2;
        color: white;
    }
    
    .text-center a {
        color: #6366f1;
        text-decoration: none;
        font-weight: 500;
    }
    
    .text-center a:hover {
        text-decoration: underline;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .auth-container {
            flex-direction: column;
        }
    
        .auth-brand-side {
            padding: 40px 20px;
        }
    
        .auth-form-side {
            padding: 40px 20px;
        }
    
        .brand-content h1 {
            font-size: 2rem;
        }
    
        .brand-content p {
            font-size: 1rem;
        }
    
        .form-wrapper {
            max-width: 350px;
        }
    }
</style>
@endpush

@section('content')
<div class="auth-container">
    <div class="auth-brand-side">
        <div class="brand-content">
            <i class="fas fa-arrow-right brand-icon"></i>
            <h1>مرحباً بك في منصتنا</h1>
            <p>انضم إلينا الآن واستمتع بتجربة فريدة ومميزات حصرية لإدارة مهامك بكفاءة.</p>
            <ul class="brand-features">
                <li><i class="fas fa-lock"></i> تسجيل دخول آمن ومحمي</li>
                <li><i class="fas fa-tachometer-alt"></i> وصول سريع لحسابك</li>
                <li><i class="fas fa-mobile-alt"></i> متوافق مع جميع الأجهزة</li>
                <li><i class="fas fa-clock"></i> متاح على مدار الساعة</li>
            </ul>
        </div>
    </div>
    <div class="auth-form-side">
        <div class="form-wrapper">
            <h2><i class="fas fa-user-plus"></i> إنشاء حساب جديد</h2>
            <p class="subtitle">املأ البيانات التالية للانضمام إلى مجتمعنا.</p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>حدث خطأ!</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf

                <div class="form-group">
                    <label for="name"><i class="fas fa-user"></i> الاسم الكامل</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                </div>

                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> البريد الإلكتروني</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                </div>

                <div class="form-group">
                    <label for="phone"><i class="fas fa-phone"></i> رقم الهاتف</label>
                    <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required>
                </div>

                <div class="form-group">
                    <label for="password"><i class="fas fa-lock"></i> كلمة المرور</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                    <i class="fas fa-eye password-toggle"></i>
                </div>

                <div class="form-group">
                    <label for="password-confirm"><i class="fas fa-lock"></i> تأكيد كلمة المرور</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                </div>

                <button type="submit" class="btn-submit">
                    إنشاء الحساب <i class="fas fa-arrow-right"></i>
                </button>
            </form>

            <div class="divider">أو</div>

            <div class="social-login">
                <a href="#" class="btn-social btn-google"><i class="fab fa-google"></i> Google</a>
                <a href="#" class="btn-social btn-facebook"><i class="fab fa-facebook-f"></i> Facebook</a>
            </div>

            <p class="text-center">لديك حساب بالفعل؟ <a href="{{ route('login') }}">تسجيل الدخول</a></p>
        </div>
    </div>
</div>
@endsection