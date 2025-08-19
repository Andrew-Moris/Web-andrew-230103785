<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Purchase;

class PurchaseController extends Controller
{
    /**
     * Display the user's purchases.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $purchases = Purchase::where('user_id', $user->id)
                               ->with('product') // Eager load product details
                               ->latest() // Order by creation date, newest first
                               ->paginate(15); // Paginate results

        return view('my-purchases', compact('purchases'));
    }
}
