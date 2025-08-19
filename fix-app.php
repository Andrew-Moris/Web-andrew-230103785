<?php
/**
 * Laravel Application Fix Script
 * This script will help fix the common issues in your Laravel application
 */

echo "🔧 Laravel Application Fix Script\n";
echo "=================================\n\n";

// Check if we're in the right directory
if (!file_exists('artisan')) {
    echo "❌ Error: Please run this script from your Laravel project root directory.\n";
    exit(1);
}

echo "✅ Found Laravel project\n";

// Step 1: Clear all caches
echo "\n📋 Step 1: Clearing caches...\n";
system('php artisan cache:clear');
system('php artisan config:clear');
system('php artisan route:clear');
system('php artisan view:clear');
echo "✅ Caches cleared\n";

// Step 2: Create storage link
echo "\n📋 Step 2: Creating storage link...\n";
system('php artisan storage:link');
echo "✅ Storage link created\n";

// Step 3: Run migrations
echo "\n📋 Step 3: Running migrations...\n";
system('php artisan migrate --force');
echo "✅ Migrations completed\n";

// Step 4: Check database connection
echo "\n📋 Step 4: Testing database connection...\n";
try {
    require_once 'vendor/autoload.php';
    
    $app = require_once 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    DB::connection()->getPdo();
    echo "✅ Database connection successful\n";
} catch (Exception $e) {
    echo "❌ Database connection failed: " . $e->getMessage() . "\n";
    echo "💡 Please check your .env database settings\n";
}

echo "\n🎉 Fix script completed!\n";
echo "\n📝 Next steps:\n";
echo "1. Visit: http://127.0.0.1:8000/make-me-admin (to get admin permissions)\n";
echo "2. Then visit: http://127.0.0.1:8000/permissions (to manage user permissions)\n";
echo "3. Then visit: http://127.0.0.1:8000/products (to manage products)\n";
echo "\n🚀 Start your server with: php artisan serve\n";
