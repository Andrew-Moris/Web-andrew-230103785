<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rules\Password;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('throttle:5,1')->only('login', 'register');
    }
    
    public function showLogin()
    {
        return view('login');
    }

    public function showRegister()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|min:2|max:50',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => [
                    'required',
                    'string',
                    'min:6',
                    'max:50',
                    'confirmed',
                ],
                'phone' => 'nullable|string|min:10|max:15',
            ], [
                'name.required' => 'الاسم مطلوب',
                'name.min' => 'الاسم يجب أن يكون على الأقل حرفين',
                'email.required' => 'البريد الإلكتروني مطلوب',
                'email.email' => 'البريد الإلكتروني غير صحيح',
                'email.unique' => 'البريد الإلكتروني مستخدم بالفعل',
                'password.required' => 'كلمة المرور مطلوبة',
                'password.min' => 'كلمة المرور يجب أن تكون على الأقل 6 أحرف',
                'password.confirmed' => 'تأكيد كلمة المرور غير متطابق',
                'phone.min' => 'رقم الهاتف يجب أن يكون على الأقل 10 أرقام',
            ]);

            try {
                DB::connection()->getPdo();
            } catch (\Exception $e) {
                Log::error('Database connection failed: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'خطأ في الاتصال بقاعدة البيانات'
                ], 500);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
                'email_verified_at' => null,
            ]);
            
            UserPermission::create([
                'user_id' => $user->id,
                'role' => 'viewer',
                'permissions' => ['reports']
            ]);

            Auth::login($user);

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء الحساب بنجاح!',
                'redirect' => '/'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى التحقق من البيانات المدخلة',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء الحساب. يرجى المحاولة مرة أخرى.'
            ], 500);
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ], [
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون على الأقل 6 أحرف',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        $ipAddress = $request->ip();
        $cacheKey = 'login_attempts_' . $ipAddress;
        
        if (Cache::has($cacheKey)) {
            $attempts = Cache::get($cacheKey);
            if ($attempts >= 5) {
                return response()->json([
                    'success' => false,
                    'message' => 'محاولات تسجيل دخول كثيرة جداً. يرجى المحاولة مرة أخرى خلال 15 دقيقة.'
                ], 429);
            }
        }

        if (Auth::attempt($credentials, $remember)) {
            Cache::forget($cacheKey);
            
            $user = Auth::user();
            
            if (!$user->userPermissions) {
                UserPermission::create([
                    'user_id' => $user->id,
                    'role' => 'viewer',
                    'permissions' => ['reports'],
                ]);
                $user = User::with('userPermissions')->find($user->id);
            }
            
            $permissions = $user->userPermissions;
            
            session([
                'login_success' => true,
                'user_role' => $permissions ? $permissions->role : 'viewer',
                'user_permissions' => $permissions ? $permissions->permissions : ['reports']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل الدخول بنجاح! أهلاً بك مرة أخرى.',
                'redirect' => '/',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $permissions ? $permissions->role : 'viewer',
                    'permissions' => $permissions ? $permissions->permissions : ['reports']
                ]
            ]);
        }

        $attempts = Cache::get($cacheKey, 0) + 1;
        Cache::put($cacheKey, $attempts, 900);

        sleep(1);

        return response()->json([
            'success' => false,
            'message' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة. يرجى التحقق من البيانات والمحاولة مرة أخرى.'
        ], 401);
    }

    public function redirectToGoogle()
    {
        try {
            return Socialite::driver('google')->redirect();
        } catch (\Exception $e) {
            Log::error('Google redirect failed: ' . $e->getMessage());
            return redirect('/login')->with('error', 'فشل في الاتصال بـ Google. يرجى المحاولة مرة أخرى.');
        }
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // Check if user exists
            $user = User::where('email', $googleUser->getEmail())->first();

            if (!$user) {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'email_verified_at' => now(),
                ]);
            }

            // Ensure user has permissions
            UserPermission::updateOrCreate([
                'user_id' => $user->id
            ], [
                'role' => 'viewer',
                'permissions' => ['reports']
            ]);

            // Login user
            Auth::login($user);

            return redirect('/')->with('success', 'تم تسجيل الدخول بنجاح باستخدام Google!');

        } catch (\Exception $e) {
            Log::error('Google callback failed: ' . $e->getMessage());
            return redirect('/login')->with('error', 'فشل في تسجيل الدخول باستخدام Google. يرجى المحاولة مرة أخرى.');
        }
    }

    // Facebook OAuth methods
    public function redirectToFacebook()
    {
        try {
            return Socialite::driver('facebook')->redirect();
        } catch (\Exception $e) {
            Log::error('Facebook redirect failed: ' . $e->getMessage());
            return redirect('/login')->with('error', 'فشل في الاتصال بـ Facebook. يرجى المحاولة مرة أخرى.');
        }
    }

    public function handleFacebookCallback()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();
            
            // Check if user exists
            $user = User::where('email', $facebookUser->getEmail())->first();

            if (!$user) {
                // Create new user
                $user = User::create([
                    'name' => $facebookUser->getName(),
                    'email' => $facebookUser->getEmail(),
                    'email_verified_at' => now(),
                ]);
            }

            // Ensure user has permissions
            UserPermission::updateOrCreate([
                'user_id' => $user->id
            ], [
                'role' => 'viewer',
                'permissions' => ['reports']
            ]);

            // Login user
            Auth::login($user);

            return redirect('/')->with('success', 'تم تسجيل الدخول بنجاح باستخدام Facebook!');

        } catch (\Exception $e) {
            Log::error('Facebook callback failed: ' . $e->getMessage());
            return redirect('/login')->with('error', 'فشل في تسجيل الدخول باستخدام Facebook. يرجى المحاولة مرة أخرى.');
        }
    }

    // Microsoft OAuth methods
    public function redirectToMicrosoft()
    {
        try {
            return Socialite::driver('microsoft')->redirect();
        } catch (\Exception $e) {
            Log::error('Microsoft redirect failed: ' . $e->getMessage());
            return redirect('/login')->with('error', 'فشل في الاتصال بـ Microsoft. يرجى المحاولة مرة أخرى.');
        }
    }

    public function handleMicrosoftCallback()
    {
        try {
            $microsoftUser = Socialite::driver('microsoft')->user();
            
            // Check if user exists
            $user = User::where('email', $microsoftUser->getEmail())->first();

            if (!$user) {
                // Create new user
                $user = User::create([
                    'name' => $microsoftUser->getName(),
                    'email' => $microsoftUser->getEmail(),
                    'email_verified_at' => now(),
                ]);
            }

            // Ensure user has permissions
            UserPermission::updateOrCreate([
                'user_id' => $user->id
            ], [
                'role' => 'viewer',
                'permissions' => ['reports']
            ]);

            // Login user
            Auth::login($user);

            return redirect('/')->with('success', 'تم تسجيل الدخول بنجاح باستخدام Microsoft!');

        } catch (\Exception $e) {
            Log::error('Microsoft callback failed: ' . $e->getMessage());
            return redirect('/login')->with('error', 'فشل في تسجيل الدخول باستخدام Microsoft. يرجى المحاولة مرة أخرى.');
        }
    }

    public function logout()
    {
        Auth::logout();
        Session::flush();
        return redirect('/login')->with('success', 'تم تسجيل الخروج بنجاح');
    }

    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255'
        ], [
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
        ]);
        
        $email = $request->input('email');
        
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'success' => false,
                'message' => 'تنسيق البريد الإلكتروني غير صحيح'
            ], 400);
        }
        
        $exists = User::where('email', $email)->exists();
        
        return response()->json([
            'available' => !$exists,
            'message' => $exists ? 'البريد الإلكتروني مستخدم بالفعل' : 'البريد الإلكتروني متاح'
        ]);
    }
}