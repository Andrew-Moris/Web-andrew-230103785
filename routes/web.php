<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\GpaController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\PurchaseController;
use App\Models\User;
use App\Models\Product;

Route::get('/', function () {
    return view('welcome');
});

// General dashboard route (for all authenticated users)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

// Dashboard API routes (accessible to all authenticated users)
Route::group(['middleware' => ['auth']], function () {
    Route::prefix('api')->group(function () {
        Route::get('/users-data', [App\Http\Controllers\DashboardApiController::class, 'getUsersData']);
        Route::get('/products-data', [App\Http\Controllers\DashboardApiController::class, 'getProductsData']);
        Route::get('/statistics-data', [App\Http\Controllers\DashboardApiController::class, 'getStatisticsData']);
    });
});

Route::get('/grant-admin-to-andrew', [PermissionController::class, 'grantAdminPermissionsToAndrew']);







// Product management routes
Route::middleware(['auth'])->group(function () {
    Route::resource('products', App\Http\Controllers\ProductController::class);
});

Route::get('/products/top', [App\Http\Controllers\ProductController::class, 'getTopProducts'])->name('products.top');

use App\Http\Controllers\ReportController;

Route::get('/reports', [ReportController::class, 'index'])->name('reports');

use App\Http\Controllers\UserController;

Route::get('/users', [PermissionController::class, 'index'])->name('users');

use App\Http\Controllers\SettingsController;

Route::get('/settings', [SettingsController::class, 'index'])->name('settings');

Route::get('/even-numbers', function () {
    return view('even-numbers');
})->name('even-numbers');

Route::get('/tools/even-numbers', function () {
    return 'Even Numbers Tool Page';
})->name('tools.even-numbers');

Route::get('/multiplication', function () {
    return view('multiplication');
})->name('multiplication');

Route::get('/tools/multiplication', function () {
    return 'Multiplication Tool Page';
})->name('tools.multiplication');

// Advanced Catalog with search and filtering
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog');
Route::get('/catalog/product/{id}', [CatalogController::class, 'getProduct'])->name('catalog.product');
Route::post('/catalog/purchase', [CatalogController::class, 'purchase'])->middleware('auth')->name('catalog.purchase');
Route::get('/catalog/suggestions', [CatalogController::class, 'searchSuggestions'])->name('catalog.suggestions');

// Admin Credit Management
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/credits', [App\Http\Controllers\AdminCreditController::class, 'index'])->name('admin.credits.index');
        Route::post('/admin/credits/update/{user}', [App\Http\Controllers\AdminCreditController::class, 'update'])->name('admin.credits.update');

    // User Credit Top-up
    Route::get('/credits/add', [App\Http\Controllers\CreditController::class, 'show'])->name('credits.add');
        Route::post('/credits/add', [App\Http\Controllers\CreditController::class, 'add'])->name('credits.add.post');

    // User's Purchases Page
    Route::get('/my-purchases', [PurchaseController::class, 'index'])->name('my-purchases');
});

// GPA Calculator routes
Route::get('/gpa', [App\Http\Controllers\GpaController::class, 'index'])->name('gpa.index');
Route::post('/gpa/calculate', [App\Http\Controllers\GpaController::class, 'calculate'])->name('gpa.calculate');
Route::get('/gpa/sample-data', [App\Http\Controllers\GpaController::class, 'getSampleData'])->name('gpa.sample');

// مسارات المصادقة (Authentication)
Route::middleware('web')->group(function () {
    // صفحات تسجيل الدخول والتسجيل
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

    // تسجيل الخروج
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Google OAuth
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('google.login');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('google.callback');

    // Facebook OAuth
    Route::get('/auth/facebook', [AuthController::class, 'redirectToFacebook'])->name('facebook.login');
    Route::get('/auth/facebook/callback', [AuthController::class, 'handleFacebookCallback'])->name('facebook.callback');

    // Microsoft OAuth
    Route::get('/auth/microsoft', [AuthController::class, 'redirectToMicrosoft'])->name('microsoft.login');
    Route::get('/auth/microsoft/callback', [AuthController::class, 'handleMicrosoftCallback'])->name('microsoft.callback');
});

// التحقق من البريد الإلكتروني
Route::post('/check-email', [AuthController::class, 'checkEmail'])->name('check.email');

// مسارات الصلاحيات (للأدمن فقط)
Route::middleware(['web', 'auth', 'admin'])->group(function () {
    Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions');
    Route::post('/permissions/update', [PermissionController::class, 'updatePermissions'])->name('permissions.update');
    Route::get('/permissions/user/{userId}', [PermissionController::class, 'getUserPermissions'])->name('permissions.user');
    Route::delete('/permissions/delete/{userId}', [PermissionController::class, 'deletePermissions'])->name('permissions.delete');

    Route::post('/roles/create', [PermissionController::class, 'createRole'])->name('permissions.createRole');
    Route::post('/roles/update', [PermissionController::class, 'updateRole'])->name('permissions.updateRole');
    Route::post('/roles/delete', [PermissionController::class, 'deleteRole'])->name('permissions.deleteRole');
    Route::post('/users/update-role', [PermissionController::class, 'updateUserRole'])->name('permissions.updateUserRole');

    // مسارات إدارة المستخدمين الإدمن
    Route::get('/admin/create-user', [AdminUserController::class, 'create'])->name('admin.create-user');
    Route::post('/admin/store-user', [AdminUserController::class, 'store'])->name('admin.store-user');
    Route::get('/admin/edit-user/{id}', [AdminUserController::class, 'edit'])->name('admin.edit-user');
    Route::put('/admin/update-user/{id}', [AdminUserController::class, 'update'])->name('admin.update-user');
    Route::delete('/admin/delete-user/{id}', [AdminUserController::class, 'destroy'])->name('admin.delete-user');
});



// API Routes (للأدمن فقط)
Route::prefix('api')->middleware(['web', 'auth', 'admin'])->group(function () {
    Route::get('/users', [PermissionController::class, 'getAllUsers'])->name('api.users');
});

Route::middleware(['web', 'auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return '<h1>Admin Dashboard</h1>'; // Replace with your admin dashboard view
    })->name('dashboard');
    // Add other admin routes for managing users and products here
});

// مسار مؤقت لإصلاح المستخدمين
Route::get('/fix-permissions', function() {
    $users = App\Models\User::whereDoesntHave('userPermissions')->get();

    foreach ($users as $user) {
        App\Models\UserPermission::create([
            'user_id' => $user->id,
            'role' => 'viewer',
            'permissions' => ['reports'],
        ]);
    }

    return 'Fixed permissions for ' . $users->count() . ' users.';
})->name('fix.permissions');

// مسار لمنح صلاحيات الأدمن للمستخدم الحالي
Route::get('/make-me-admin', function() {
    $user = Auth::user();

    if (!$user) {
        return redirect('/login')->with('error', 'يجب تسجيل الدخول أولاً');
    }

    // Update or create admin permissions for current user
    App\Models\UserPermission::updateOrCreate(
        ['user_id' => $user->id],
        [
            'role' => 'admin',
            'permissions' => ['create', 'edit', 'delete', 'reports', 'users']
        ]
    );

    return redirect('/permissions')->with('success', 'تم منحك صلاحيات الأدمن بنجاح! يمكنك الآن إدارة جميع المستخدمين.');
})->middleware('auth')->name('make.admin');



// مسار لإنشاء مستخدم أدمن جديد
Route::get('/create-admin-user', function() {
    // Generate random password
    $password = Str::random(12);
    $email = 'admin@example.com';

    // Check if admin user already exists
    $existingUser = App\Models\User::where('email', $email)->first();
    if ($existingUser) {
        // Update password for existing admin
        $existingUser->update([
            'password' => Hash::make($password)
        ]);
        $user = $existingUser;
    } else {
        // Create new admin user
        $user = App\Models\User::create([
            'name' => 'Admin User',
            'email' => $email,
            'password' => Hash::make($password),
            'email_verified_at' => now()
        ]);
    }

    // Create admin permissions
    App\Models\UserPermission::updateOrCreate(
        ['user_id' => $user->id],
        [
            'role' => 'admin',
            'permissions' => ['create', 'edit', 'delete', 'reports', 'users']
        ]
    );

    return response()->json([
        'success' => true,
        'message' => 'تم إنشاء مستخدم الأدمن بنجاح!',
        'credentials' => [
            'email' => $email,
            'password' => $password,
            'login_url' => url('/login')
        ]
    ]);
})->name('create.admin.user');
