<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تسجيل الدخول | تصميم حديث</title>
    
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
                <span class="mb-3 text-4xl font-bold">مرحباً بعودتك</span>
                <span class="font-light text-gray-500 mb-8">
                    أدخل بياناتك للوصول إلى حسابك والاستمتاع بجميع خدماتنا
                </span>

                <div id="alert-container" class="mb-4"></div>

                <form id="loginForm" method="POST" action="{{ route('login.submit') }}" novalidate>
                    @csrf
                    
                    <!-- Email Input -->
                    <div class="py-4">
                        <label for="email" class="mb-2 text-md font-medium text-gray-700">البريد الإلكتروني</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                            </div>
                            <input 
                                id="email" 
                                type="email" 
                                class="w-full p-3 pr-10 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" 
                                name="email" 
                                value="{{ old('email') }}" 
                                required 
                                autocomplete="email" 
                                autofocus 
                                placeholder="example@email.com">
                        </div>
                        <div class="text-red-500 text-sm mt-1" id="email-error"></div>
                    </div>

                    <!-- Password Input -->
                    <div class="py-4">
                        <label for="password" class="mb-2 text-md font-medium text-gray-700">كلمة المرور</label>
                         <div class="relative">
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                               <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <input 
                                id="password" 
                                type="password" 
                                class="w-full p-3 pr-10 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" 
                                name="password" 
                                required 
                                autocomplete="current-password" 
                                placeholder="أدخل كلمة المرور">
                        </div>
                        <div class="text-red-500 text-sm mt-1" id="password-error"></div>
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex justify-between w-full py-4">
                        <div class="mr-24">
                            <input type="checkbox" name="remember" id="remember" class="mr-1 accent-indigo-600" {{ old('remember') ? 'checked' : '' }}>
                            <label for="remember" class="text-md text-gray-600">تذكرني</label>
                        </div>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" id="loginBtn" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white p-3 rounded-lg mb-6 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-4 focus:ring-indigo-300 transition-all duration-300 ease-in-out flex items-center justify-center disabled:opacity-70 disabled:cursor-not-allowed">
                        <span class="btn-text font-semibold">تسجيل الدخول</span>
                        <span class="btn-spinner hidden">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </button>
                    
                    <!-- Social Login -->
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

                    <div class="text-center mt-8 text-gray-600">
                        ليس لديك حساب؟
                        <a href="{{ route('register') }}" class="font-semibold text-indigo-600 hover:text-indigo-700 hover:underline">إنشاء حساب جديد</a>
                    </div>
                </form>
            </div>

            <!-- Image & Branding Section -->
            <div class="relative hidden md:block md:w-1/2">
                <img src="https://images.unsplash.com/photo-1585314958936-59c41a364ebd?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80" 
                     alt="صورة تسجيل الدخول" 
                     class="w-full h-full object-cover rounded-r-2xl"
                     onerror="this.onerror=null;this.src='https://placehold.co/400x680/6366f1/ffffff?text=Welcome!';"
                >
                <!-- Overlay -->
                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent rounded-r-2xl"></div>
                <!-- Text on Image -->
                <div class="absolute bottom-10 right-10 text-white text-right">
                    <h2 class="text-3xl font-bold">وصول آمن وسريع</h2>
                    <p class="max-w-xs mt-2 opacity-90">منصتنا تضمن لك تجربة استخدام سلسة ومحمية بالكامل.</p>
                </div>
            </div>

        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const loginForm = document.getElementById('loginForm');
        const submitBtn = document.getElementById('loginBtn');
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
                    <span class="mr-2">${icon}</span>
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

        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            clearErrors();

            // Show loading state
            submitBtn.disabled = true;
            btnText.classList.add('hidden');
            btnSpinner.classList.remove('hidden');

            const formData = new FormData(loginForm);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            try {
                const response = await fetch(loginForm.action, {
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
                            const errorEl = document.getElementById(`${key}-error`);
                            const inputEl = document.getElementById(key);
                            if (errorEl) {
                                errorEl.textContent = result.errors[key][0];
                            }
                            if(inputEl) {
                                inputEl.classList.add('border-red-500');
                            }
                        });
                        // Show a general message if available
                        if(result.message) {
                           showAlert(result.message, 'danger');
                        }
                    } else {
                        // Handle other HTTP errors
                        showAlert(result.message || 'حدث خطأ غير متوقع.', 'danger');
                    }
                } else {
                    // Handle success
                    if (result.success) {
                        showAlert(result.message, 'success');
                        setTimeout(() => {
                            window.location.href = result.redirect || '/';
                        }, 1500);
                    } else {
                        showAlert(result.message || 'فشل تسجيل الدخول.', 'danger');
                    }
                }
            } catch (error) {
                // Handle network errors
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
