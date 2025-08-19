<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 10);
            $products = Product::orderBy('created_at', 'desc')->paginate($perPage);
            return view('products.index', compact('products'));
        } catch (\Exception $e) {
            Log::error('Error fetching products: ' . $e->getMessage());
            return redirect()->back()->with('error', 'حدث خطأ أثناء جلب المنتجات');
        }
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        // Check if user has create permission
        $user = Auth::user();
        if ($user->userPermissions && !in_array('create', $user->userPermissions->permissions)) {
            return redirect()->back()->with('error', 'ليس لديك صلاحيات لإضافة منتجات جديدة');
        }
        return view('products.create');
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:products',
            'model' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'name.required' => 'اسم المنتج مطلوب',
            'code.required' => 'الكود مطلوب',
            'code.unique' => 'هذا الكود مستخدم بالفعل',
            'price.required' => 'السعر مطلوب',
            'price.min' => 'السعر يجب أن يكون أكبر من صفر',
            'image.image' => 'يجب أن يكون الملف صورة'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $product = new Product($request->all());
            
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('products', 'public');
                $product->image = $imagePath;
            }

            $product->save();

            return redirect()->route('products.index')
                ->with('success', 'تم إضافة المنتج بنجاح');

        } catch (\Exception $e) {
            Log::error('Error creating product: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إضافة المنتج');
        }
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit($id)
    {
        // Check if user has edit permission
        $user = Auth::user();
        if ($user->userPermissions && !in_array('edit', $user->userPermissions->permissions)) {
            return redirect()->back()->with('error', 'ليس لديك صلاحيات لتعديل المنتجات');
        }

        try {
            $product = Product::findOrFail($id);
            return view('products.edit', compact('product'));
        } catch (\Exception $e) {
            Log::error('Error fetching product: ' . $e->getMessage());
            return redirect()->route('products.index')
                ->with('error', 'منتج غير موجود');
        }
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:products,code,' . $id,
            'model' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ], [
            'name.required' => 'اسم المنتج مطلوب',
            'code.required' => 'الكود مطلوب',
            'code.unique' => 'هذا الكود مستخدم بالفعل',
            'price.required' => 'السعر مطلوب',
            'price.min' => 'السعر يجب أن يكون أكبر من صفر',
            'image.image' => 'يجب أن يكون الملف صورة'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $product = Product::findOrFail($id);
            $product->update($request->all());

            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $imagePath = $request->file('image')->store('products', 'public');
                $product->image = $imagePath;
                $product->save();
            }

            return redirect()->route('products.index')
                ->with('success', 'تم تحديث المنتج بنجاح');

        } catch (\Exception $e) {
            Log::error('Error updating product: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تحديث المنتج');
        }
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy($id)
    {
        // Check if user has delete permission
        $user = Auth::user();
        if ($user->userPermissions && !in_array('delete', $user->userPermissions->permissions)) {
            return redirect()->back()->with('error', 'ليس لديك صلاحيات لحذف المنتجات');
        }

        try {
            $product = Product::findOrFail($id);
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->delete();

            return redirect()->route('products.index')
                ->with('success', 'تم حذف المنتج بنجاح');

        } catch (\Exception $e) {
            Log::error('Error deleting product: ' . $e->getMessage());
            return redirect()->route('products.index')
                ->with('error', 'حدث خطأ أثناء حذف المنتج');
        }
    }

    public function getTopProducts()
    {
        try {
            $topProducts = Product::orderBy('created_at', 'desc')->take(5)->get();
            return response()->json($topProducts);
        } catch (\Exception $e) {
            Log::error('Error fetching top products: ' . $e->getMessage());
            return response()->json(['error' => 'حدث خطأ أثناء جلب أفضل المنتجات'], 500);
        }
    }
}
