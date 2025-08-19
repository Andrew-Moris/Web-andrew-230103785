<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;

class ReportController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalProducts = Product::count();

        $newUsersLast7Days = User::where('created_at', '>=', now()->subDays(7))->count();
        $newProductsLast7Days = Product::where('created_at', '>=', now()->subDays(7))->count();

        $amountSpent = 4645.00;
        $purchaseRoas = 221;
        $frequency = 2.0;
        $purchases = 198;
        $costPerLead = 423;

        $campaignPerformance = [
            ['campaign' => '50 off clearance', 'linkClicks' => 664, 'reach' => 840, 'clicks' => 717, 'leads' => 741, 'purchases' => 587, 'ctr' => '77,000%', 'purchaseCv' => 382, 'purchaseRoas' => 442, 'costPerLead' => '$104.00'],
            ['campaign' => 'Spring sales', 'linkClicks' => 637, 'reach' => 795, 'clicks' => 491, 'leads' => 306, 'purchases' => 729, 'ctr' => '25,100%', 'purchaseCv' => 778, 'purchaseRoas' => 468, 'costPerLead' => '$214.00'],
            ['campaign' => 'Cyber monday sale', 'linkClicks' => 490, 'reach' => 700, 'clicks' => 667, 'leads' => 779, 'purchases' => 395, 'ctr' => '84,200%', 'purchaseCv' => 202, 'purchaseRoas' => 663, 'costPerLead' => '$342.00'],
            ['campaign' => 'Black friday', 'linkClicks' => 259, 'reach' => 649, 'clicks' => 819, 'leads' => 810, 'purchases' => 675, 'ctr' => '62,500%', 'purchaseCv' => 573, 'purchaseRoas' => 587, 'costPerLead' => '$412.00'],
        ];

        return view('reports', compact('totalUsers', 'totalProducts', 'newUsersLast7Days', 'newProductsLast7Days', 'amountSpent', 'purchaseRoas', 'frequency', 'purchases', 'costPerLead', 'campaignPerformance'));
    }
}
