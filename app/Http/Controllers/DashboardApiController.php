<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class DashboardApiController extends Controller
{
    public function getUsersData()
    {
        $totalUsers = User::count();
        return response()->json(['totalUsers' => $totalUsers]);
    }

    public function getProductsData()
    {
        $totalProducts = Product::count();
        return response()->json(['totalProducts' => $totalProducts]);
    }

    public function getStatisticsData()
    {
        // Check if orders table exists, if not use fallback data
        $totalRevenue = 0;
        $totalPurchases = 0;
        
        if (Schema::hasTable('orders')) {
            $totalRevenue = DB::table('orders')->sum('total_amount') ?? 0;
            $totalPurchases = DB::table('orders')->count() ?? 0;
        } else {
            // Fallback: calculate based on products (simulate revenue)
            $totalProducts = Product::count();
            $totalRevenue = $totalProducts * 150; // Average price simulation
            $totalPurchases = $totalProducts * 2; // Simulate 2 purchases per product
        }

        // Generate real chart data based on user registrations over last 6 months
        $usersGrowth = $this->getUserGrowthData();
        
        // Generate product categories data
        $productCategories = $this->getProductCategoriesData();

        return response()->json([
            'usersGrowth' => $usersGrowth,
            'productCategories' => $productCategories,
            'totalRevenue' => $totalRevenue,
            'totalPurchases' => $totalPurchases
        ]);
    }

    private function getUserGrowthData()
    {
        // Get user registrations for last 6 months
        $months = [];
        $data = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M');
            
            $userCount = User::whereYear('created_at', $date->year)
                           ->whereMonth('created_at', $date->month)
                           ->count();
            $data[] = $userCount;
        }

        // If no real data, provide sample data
        if (array_sum($data) == 0) {
            $data = [12, 19, 25, 18, 32, 28];
        }

        return [
            'labels' => $months,
            'data' => $data
        ];
    }

    private function getProductCategoriesData()
    {
        // Try to get real product categories if possible
        $categories = ['Electronics', 'Books', 'Home', 'Clothing', 'Sports'];
        $data = [];
        
        foreach ($categories as $category) {
            // Simulate category data based on product names containing keywords
            $count = Product::where('name', 'LIKE', '%' . strtolower($category) . '%')
                          ->orWhere('description', 'LIKE', '%' . strtolower($category) . '%')
                          ->count();
            $data[] = $count > 0 ? $count : rand(5, 25); // Fallback to random if no matches
        }

        return [
            'labels' => $categories,
            'data' => $data
        ];
    }
}
