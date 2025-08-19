<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Purchase;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class CatalogController extends Controller
{
    /**
     * Display the product catalog with advanced search and filtering
     */
    public function index(Request $request)
    {
        $query = Product::query();
        
        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('code', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('model', 'LIKE', "%{$searchTerm}%");
            });
        }
        
        // Price range filtering
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        
        // Category filtering (based on product name patterns)
        if ($request->filled('category')) {
            $category = $request->category;
            switch ($category) {
                case 'electronics':
                    $query->where(function($q) {
                        $q->where('name', 'LIKE', '%TV%')
                          ->orWhere('name', 'LIKE', '%Phone%')
                          ->orWhere('name', 'LIKE', '%Laptop%')
                          ->orWhere('name', 'LIKE', '%Computer%');
                    });
                    break;
                case 'appliances':
                    $query->where(function($q) {
                        $q->where('name', 'LIKE', '%Refrigerator%')
                          ->orWhere('name', 'LIKE', '%Washing%')
                          ->orWhere('name', 'LIKE', '%Microwave%')
                          ->orWhere('name', 'LIKE', '%Oven%');
                    });
                    break;
                case 'furniture':
                    $query->where(function($q) {
                        $q->where('name', 'LIKE', '%Chair%')
                          ->orWhere('name', 'LIKE', '%Table%')
                          ->orWhere('name', 'LIKE', '%Sofa%')
                          ->orWhere('name', 'LIKE', '%Bed%');
                    });
                    break;
            }
        }
        
        // Sorting
        $sortBy = $request->get('sort', 'name');
        $sortOrder = $request->get('order', 'asc');
        
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'name':
            default:
                $query->orderBy('name', $sortOrder);
                break;
        }
        
        // Get products with pagination
        $products = $query->paginate(12)->appends($request->query());
        
        // Get price range for filters
        $priceRange = Product::selectRaw('MIN(price) as min_price, MAX(price) as max_price')->first();
        
        // Get categories count
        $categories = [
            'electronics' => Product::where(function($q) {
                $q->where('name', 'LIKE', '%TV%')
                  ->orWhere('name', 'LIKE', '%Phone%')
                  ->orWhere('name', 'LIKE', '%Laptop%')
                  ->orWhere('name', 'LIKE', '%Computer%');
            })->count(),
            'appliances' => Product::where(function($q) {
                $q->where('name', 'LIKE', '%Refrigerator%')
                  ->orWhere('name', 'LIKE', '%Washing%')
                  ->orWhere('name', 'LIKE', '%Microwave%')
                  ->orWhere('name', 'LIKE', '%Oven%');
            })->count(),
            'furniture' => Product::where(function($q) {
                $q->where('name', 'LIKE', '%Chair%')
                  ->orWhere('name', 'LIKE', '%Table%')
                  ->orWhere('name', 'LIKE', '%Sofa%')
                  ->orWhere('name', 'LIKE', '%Bed%');
            })->count(),
        ];
        
                $userCredit = Auth::check() ? Auth::user()->credit : 0;

        return view('catalog', compact('products', 'priceRange', 'categories', 'userCredit'));
    }
    
    /**
     * Get product details via AJAX
     */
    public function getProduct($id)
    {
        try {
            $product = Product::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'product' => [
                    'id' => $product->id,
                    'name' => $product->name,
                    'code' => $product->code,
                    'model' => $product->model,
                    'price' => $product->price,
                    'description' => $product->description,
                    'image' => $product->image ? asset('storage/' . $product->image) : null,
                    'created_at' => $product->created_at->format('Y-m-d')
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
    }
    
        /**
     * Handle product purchase
     */
    public function purchase(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $user = User::find(Auth::id());
        $product = Product::findOrFail($request->product_id);

        if ($user->credit < $product->price) {
            return redirect()->route('catalog')->with('error', 'Your credit is insufficient to complete this purchase.');
        }

        try {
            DB::transaction(function () use ($user, $product) {
                $user->decrement('credit', $product->price);
                $product->decrement('stock');

                Purchase::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'price_paid' => $product->price,
                    'quantity' => 1,
                ]);
            });

            return redirect()->route('my-purchases')->with('success', 'Product purchased successfully!');

        } catch (\Exception $e) {
            Log::error('Purchase failed: ' . $e->getMessage());
            return redirect()->route('catalog')->with('error', 'An error occurred during the purchase. Please try again.');
        }
    }

    /**
     * Search suggestions for autocomplete
     */
    public function searchSuggestions(Request $request)
    {
        $term = $request->get('term', '');
        
        if (strlen($term) < 2) {
            return response()->json([]);
        }
        
        $suggestions = Product::where('name', 'LIKE', "%{$term}%")
            ->orWhere('model', 'LIKE', "%{$term}%")
            ->limit(10)
            ->get(['id', 'name', 'model', 'price'])
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'label' => $product->name . ' - ' . $product->model,
                    'value' => $product->name,
                    'price' => $product->price
                ];
            });
            
        return response()->json($suggestions);
    }
}
