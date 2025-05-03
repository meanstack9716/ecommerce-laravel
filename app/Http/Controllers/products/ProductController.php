<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\ProductVariant;
use App\Models\ProductGallery;
use App\Enums\Sizes;

class ProductController extends Controller
{
    private $productSessionKey = 'product_data';

    public function showAddProductForm(Request $request)
    {
        $categories = Category::all();
        $subCategories = collect();
        $subSubCategories = collect();

        $route = $request->route()->getName();
        $referer = $request->headers->get('referer');
        $productData = $request->session()->get($this->productSessionKey, []);

        $routes = ['step1', 'step2', 'step3', 'step4'];

        $isComingFromLaterStep = $referer && array_filter($routes, function($r) use ($referer) {
            return str_contains($referer, $r);
        });

        if (!$isComingFromLaterStep) {
            $request->session()->forget($this->productSessionKey);
        }

        if (str_contains($route, 'step2')) {
            if (empty($productData['category'])) {
                return redirect()->route('products.add.step1');
            }
        }

        if (str_contains($route, 'step3')) {
            if (empty($productData['basic'])) {
                return redirect()->route('products.add.step2');
            }
        }

        if (str_contains($route, 'step4')) {
            if (empty($productData['sizes'])) {
                return redirect()->route('products.add.step3');
            }
        }
    
        $categoryId = old('category') ?? ($productData['category']['category_id'] ?? null);
        if ($categoryId) {
            $subCategories = SubCategory::where('category_id', $categoryId)->get();
        }
        
        $subCategoryId = old('sub_category') ?? ($productData['category']['sub_category_id'] ?? null);
        if ($subCategoryId) {
            $subSubCategories = SubSubCategory::where('sub_category_id', $subCategoryId)->get();
        }
        return view('forms.products.index', compact('categories', 'subCategories', 'subSubCategories'));
    }

    public function storeProductCategoryDetails(Request $request)
    {
        $category = [
            'category_id' => $request->category,
            'sub_category_id' =>  $request->sub_category,
            'sub_sub_category_id' =>  $request->sub_sub_category,
        ];
        
        $request->session()->put($this->productSessionKey.'.category', $category);        
        return redirect()->route('products.add.step2');
    }

    public function storeProductBasicDetails(Request $request)
    {
        $basic = [
            'product_title' => $request->product_title,
            'product_description' => $request->product_description,
            'product_details' => $request->product_details,
            'product_price' => $request->product_price,
            'discount_per' => $request->discount_per,
            'product_brand' => $request->product_brand,
            'product_sku' => $request->product_sku,
            'stock_quantity' => $request->stock_quantity,
        ];
        
        $request->session()->put($this->productSessionKey.'.basic', $basic);   
        return redirect()->route('products.add.step3');
    }

    public function storeProductVariantDetails(Request $request)
    {
        $sizes = [];
        foreach ($request->sizes as $sizeKey => $sizeData) {
            $newSize = [
                'id' => $sizeKey,
                'value' => $sizeData['name'] == 'custom' ? $sizeData['custom_size'] : $sizeData['name'],
                'colors' => []
            ];
            $standardColors = $sizeData['colors']['standard'] ?? [];    
            foreach ($standardColors as $colorName => $colorDetails) {
                if (!empty($colorDetails['enabled']) && $colorDetails['enabled'] == '1') {
                    $newColor = [
                        'id' => $colorName,
                        'value' => $colorName,
                        'quantity' => $colorDetails['quantity']
                    ];
                    $newSize['colors'][] = $newColor;
                }
            }

            $customColors = $sizeData['colors']['custom'] ?? [];    
            foreach ($customColors as $colorName => $colorDetails) {
                $newColor = [
                    'id' => $colorName,
                    'value' => $colorDetails['hex'],
                    'quantity' => $colorDetails['quantity']
                ];
                $newSize['colors'][] = $newColor;
            }
            $sizes[] = $newSize;
        }

        $request->session()->put($this->productSessionKey.'.sizes', $sizes);
        $request->session()->put($this->productSessionKey.'.size_type', $request->size_type);
        return redirect()->route('products.add.step4');
    }

    public function completeProductRegistration(Request $request)
    {
        $productData = $request->session()->get($this->productSessionKey);

        if(empty($productData['basic']) || empty($productData['category']) || empty($productData['sizes'])) {
            return redirect()->route('products.add.step1');
        }

        $product = Product::create([
            'title' => $productData['basic']['product_title'],
            'description' => $productData['basic']['product_description'],
            'details' => $productData['basic']['product_details'],
            'price' => $productData['basic']['product_price'],
            'discount' => $productData['basic']['discount_per'],
            'sku' => $productData['basic']['product_sku'],
            'stock_quantity' => $productData['basic']['stock_quantity'],
            'brand' => $productData['basic']['product_brand'],
            'category_id' => $productData['category']['category_id'],
            'sub_category_id' => $productData['category']['sub_category_id'],
            'sub_sub_category_id'=> $productData['category']['sub_sub_category_id']
        ]);

        foreach ($productData['sizes'] as $sizeData) {

            $size = ProductSize::create([
                'product_id' => $product->id,
                'value' => $sizeData['value'],
                'size_type' => $productData['size_type']
            ]);
            
            foreach ($sizeData['colors'] ?? [] as $color) {
                ProductVariant::create([
                    'size_id' => $size->id,
                    'value' => $color['value'],
                    'stock_quantity' => $color['quantity'] ?? 0
                ]);
            }
        }

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('products/'.$product->id);
            $product->update([
                'thumbnail_path' => $path,
            ]);
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products/'.$product->id);
                ProductGallery::create([
                    'product_id' => $product->id,
                    'img_path' => $path,
                ]);
            }
        }
        $request->session()->forget($this->productSessionKey);
        return redirect()->route('dashboard');
    }

    public function fetchAllProducts(Request $request) {

        $limit = $request->input('limit');
        $page = $request->input('page', 1);
        $searchTerm = $request->input('searchTerm');
        $query = Product::query()->with(['gallery', 'sizes']);

        // if ($searchTerm) {
        //     $query->where(function ($q) use ($searchTerm) {
        //         $q->where('name', 'like', "%{$searchTerm}%");
        //     });
        // }

        // if ($limit) {
        //     $categories = $query->paginate($limit, ['*'], 'page', $page);            
        //     return response()->json([
        //         'data' => $categories->items()
        //     ]);
        // }

        $categories = $query->get();
        return response()->json([
            'data' => $categories
        ]);
    }
}