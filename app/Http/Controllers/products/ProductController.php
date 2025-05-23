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
use App\Models\ProductBrand;
use App\Models\Seller;
use App\Enums\Sizes;
use Illuminate\Support\Facades\URL;

class ProductController extends Controller
{
    private $productSessionKey = 'product_data';

    private function getSizeDetails($data)
    {
        $sizes = [];
        $colors = [];
        foreach ($data as $sizeKey => $sizeData) {
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
                        'name' => $colorDetails['name'],
                        'quantity' => $colorDetails['quantity'] ?? 0
                    ];
                    $newSize['colors'][] = $newColor;
                    $colors[] = $colorDetails['name'];
                }
            }

            $customColors = $sizeData['colors']['custom'] ?? [];    
            foreach ($customColors as $colorName => $colorDetails) {
                $newColor = [
                    'id' => $colorName,
                    'value' => $colorDetails['hex'],
                    'name' => $colorDetails['name'],
                    'quantity' => $colorDetails['quantity'] ?? 0
                ];
                $newSize['colors'][] = $newColor;
                $colors[] = $colorDetails['name'];
            }
            $sizes[] = $newSize;
        }
        return [
            'sizes' => $sizes,
            'colors' =>  array_values(array_unique($colors))
        ];
    }

    public function showAddProductForm(Request $request)
    {
        $categories = Category::all();
        $brands = ProductBrand::all();
        $subCategories = collect();
        $subSubCategories = collect();

        $route = $request->route()->getName();
        $previousUrl = URL::previous();
        $productData = $request->session()->get($this->productSessionKey, []);

        $routes = ['step1', 'step2', 'step3', 'step4'];

        $selectedColors = [];

        $isComingFromLaterStep = $previousUrl && array_filter($routes, function($r) use ($previousUrl) {
            return str_contains($previousUrl, $r);
        });

        if (!$isComingFromLaterStep) {
            $request->session()->forget($this->productSessionKey);
        }

        if (str_contains($route, 'step2')) {
            if (empty($productData['category'])) {
                return redirect()->route('products.add.step1');
            }
        }

        if (str_contains($route, 'step4')) {
            $selectedColors = $productData['colors'];
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
        return view('forms.products.index', compact('categories', 'subCategories', 'subSubCategories', 'brands', 'selectedColors'));
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
            'new_brand' => $request->product_brand == 'another' ? $request->new_brand : null,
            'product_sku' => $request->product_sku,
            'stock_quantity' => $request->stock_quantity,
        ];
        
        $request->session()->put($this->productSessionKey.'.basic', $basic);   
        return redirect()->route('products.add.step3');
    }

    public function storeProductVariantDetails(Request $request)
    {
        $sizeDetails = $this->getSizeDetails($request->sizes);

        $request->session()->put($this->productSessionKey.'.sizes', $sizeDetails['sizes']);
        $request->session()->put($this->productSessionKey.'.size_type', $request->size_type);
        $request->session()->put($this->productSessionKey.'.colors', $sizeDetails['colors']);
        return redirect()->route('products.add.step4');
    }

    public function completeProductRegistration(Request $request)
    {
        $seller = Seller::where('user_id', $request->user()->id)->first();
        $productData = $request->session()->get($this->productSessionKey);

        if(empty($productData['basic']) || empty($productData['category']) || empty($productData['sizes'])) {
            return redirect()->route('products.add.step1');
        }

        $brandId = null;

        if ($productData['basic']['product_brand'] == 'another') {
            $brand = ProductBrand::create([
                'name' => $productData['basic']['new_brand']
            ]);
            $brandId = $brand->id;
        } else {
            $brandId = $productData['basic']['product_brand'];
        }

        $product = Product::create([
            'seller_id' => $seller->id,
            'title' => $productData['basic']['product_title'],
            'description' => $productData['basic']['product_description'],
            'details' => $productData['basic']['product_details'],
            'price' => (float)$productData['basic']['product_price'],
            'discount_percent' => (float)$productData['basic']['discount_per'],
            'delivery_days' => mt_rand(1, 9),
            'sku' => $productData['basic']['product_sku'],
            'stock_quantity' => $productData['basic']['stock_quantity'],
            'brand_id' => $brandId,
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
                    'name' => $color['name'],
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

        if ($request->images) {
            foreach ($request->file('images') as $color => $files) {
                foreach ($files as $file) {
                    $path = $file->store('products/'.$product->id);
                    
                    ProductGallery::create([
                        'product_id' => $product->id,
                        'img_path' => $path,
                        'color' => $color === 'default' ? null : $color,
                        'is_thumbnail' => false
                    ]);
                }
            }
        }

        $request->session()->forget($this->productSessionKey);
        return redirect()->route('dashboard');
    }    

    public function getAllProductsList(Request $request) {

        $user = $request->user();
        $limit = $request->input('limit', 10);
        $search = $request->input('search');
        $categoryId = $request->input('categoryId');
        
        $query = Product::query()->with([]);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        }

        if (!$user->is_admin) {
            $query->where('seller_id', $user->sellerDetails->id);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $products = $query->paginate($limit);
        $categories = Category::all();
        return view('products.product-list', compact('products', 'limit', 'categories'));
    }

    public function getProductDetailView($id)
    {
        $product = Product::findOrFail($id);
        return view('products.details', compact('product'));
    }

    public function getEditProductForm($id)
    {
        $product = Product::findOrFail($id);    
        return view('products.edit-product', compact('product'));
    }

    public function updateProductDetails(Request $request, $id)
    {
        $product = Product::findOrFail($id);    
        $sizeDetails = $this->getSizeDetails($request->sizes);

        $product->update([
            'title' => $request->title,
            'description' => $request->description,
            'details' => $request->details,
            'price' => (float)$request->price,
            'discount_percent' => (float)$request->discount_percent,
            'sku' => $request->sku,
            'stock_quantity' => $request->stock_quantity,
        ]);

        $existingSizeIds = $product->sizes()->pluck('id')->toArray();
        $updatedSizeIds = [];

        foreach ($sizeDetails['sizes'] as $sizeData) {
            $size = ProductSize::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'value' => $sizeData['value'],
                    'size_type' => $request->size_type
                ],
                [
                    'product_id' => $product->id,
                    'value' => $sizeData['value'],
                    'size_type' => $request->size_type
                ]
            );
    
            $updatedSizeIds[] = $size->id;
    
            // Get all existing variant IDs for this size
            $existingVariantIds = $size->variants()->pluck('id')->toArray();
            $updatedVariantIds = [];
    
            // Process colors/variants
            foreach ($sizeData['colors'] ?? [] as $color) {
                $variant = ProductVariant::updateOrCreate(
                    [
                        'size_id' => $size->id,
                        'value' => $color['value']
                    ],
                    [
                        'name' => $color['name'],
                        'stock_quantity' => $color['quantity'] ?? 0
                    ]
                );
        
                $updatedVariantIds[] = $variant->id;
            }
    
            if (!empty($existingVariantIds)) {
                $variantsToDelete = array_diff($existingVariantIds, $updatedVariantIds);
                if (!empty($variantsToDelete)) {
                    ProductVariant::whereIn('id', $variantsToDelete)->delete();
                }
            }
        }

        if (!empty($existingSizeIds)) {
            $sizesToDelete = array_diff($existingSizeIds, $updatedSizeIds);
            if (!empty($sizesToDelete)) {
                ProductSize::whereIn('id', $sizesToDelete)->delete();
            }
        }

        return redirect()->route('products.list');
    }

    public function fetchProductsList(Request $request) {
        $limit = $request->input('limit');
        $page = $request->input('page', 1);
        $searchTerm = $request->input('searchTerm');
        
        $brandIds = $request->input('brandIds', $request->input('brandId'));
        $categoryIds = $request->input('categoryIds');
        $subCategoryIds = $request->input('subCategoryIds');
        $subSubCategoryIds = $request->input('subSubCategoryIds');
        
        // Price range filters
        $minPrice = $request->input('minPrice');
        $maxPrice = $request->input('maxPrice');
        
        // Size filters (multiple sizes)
        $sizes = $request->input('sizes');
        
        $query = Product::query()->with([
            'category', 
            'subCategory', 
            'subSubCategory', 
            'brand', 
            'sizes', 
            'sizes.variants', 
            'gallery',
            'reviews'
        ]);

        // Multiple Brands Selection
        if ($brandIds) {
            if (is_string($brandIds)) {
                $brandIds = array_map('trim', explode(',', $brandIds));
            }
            $query->whereIn('brand_id', $brandIds);
        }

        // Multiple Category Selection
        if ($categoryIds) {
            if (is_string($categoryIds)) {
                $categoryIds = array_map('trim', explode(',', $categoryIds));
            }
            $query->whereIn('category_id', $categoryIds);
        }

        // Multiple Sub Category Selection
        if ($subCategoryIds) {
            if (is_string($subCategoryIds)) {
                $subCategoryIds = array_map('trim', explode(',', $subCategoryIds));
            }
            $query->whereIn('sub_category_id', $subCategoryIds);
        }

        // Multiple Sub Sub Category Selection
        if ($subSubCategoryIds) {
            if (is_string($subSubCategoryIds)) {
                $subSubCategoryIds = array_map('trim', explode(',', $subSubCategoryIds));
            }
            $query->whereIn('sub_sub_category_id', $subSubCategoryIds);
        }

        // Size filter
        if ($sizes) {
            if (is_string($sizes)) {
                $sizes = explode(',', $sizes);
            }
            
            // Normalize all input sizes to lowercase
            $sizes = array_map('strtolower', $sizes);
            
            $query->whereHas('sizes', function($q) use ($sizes) {
                $q->whereRaw([
                    '$expr' => [
                        '$in' => [
                            ['$toLower' => '$value'], // Convert stored value to lowercase
                            $sizes
                        ]
                    ]
                ]);
            });
        }

        // Search term filter
        if ($searchTerm) {
            $term = trim($searchTerm);
            $likeTerm = "%$term%";

            $categorySearchIds = Category::where('name', 'like', $searchTerm)
                ->pluck('id')
                ->toArray();

            $subCategorySearchIds = SubCategory::where('name', 'like', $likeTerm)
                ->pluck('id')
                ->toArray();

            $subSubCategorySearchIds = SubSubCategory::where('name', 'like', $likeTerm)
                ->pluck('id')
                ->toArray();

            $brandSearchIds = ProductBrand::where('name', 'like', $likeTerm)
                ->pluck('id')
                ->toArray();

            $query->where(function ($q) use ($likeTerm, $categorySearchIds, $subCategorySearchIds, $subSubCategorySearchIds, $brandSearchIds) {
                $q->where('title', 'like', $likeTerm)
                    ->orWhere('description', 'like', $likeTerm)
                    ->orWhereIn('category_id', $categorySearchIds)
                    ->orWhereIn('sub_category_id', $subCategorySearchIds)
                    ->orWhereIn('brand_id', $brandSearchIds)
                    ->orWhereIn('sub_sub_category_id', $subSubCategorySearchIds);
                });
        }

        // Price range filters
        if ($minPrice !== null || $maxPrice !== null) {
            $query->where(function($q) use ($minPrice, $maxPrice) {
                // Convert input prices to float
                $min = $minPrice !== null ? (float)$minPrice : null;
                $max = $maxPrice !== null ? (float)$maxPrice : null;
                
                $conditions = [];
                
                if ($min !== null) {
                    $conditions[] = [
                        '$expr' => [
                            '$gte' => [
                                ['$toDouble' => '$final_price'],
                                $min
                            ]
                        ]
                    ];
                }
                
                if ($max !== null) {
                    $conditions[] = [
                        '$expr' => [
                            '$lte' => [
                                ['$toDouble' => '$final_price'],
                                $max
                            ]
                        ]
                    ];
                }
                
                $q->whereRaw(['$and' => $conditions]);
            });
        }   
    
    
        // Pagination or full list
        if ($limit) {
            $products = $query->paginate($limit, ['*'], 'page', $page);            
            return response()->json([
                'data' => $products->items(),
            ]);
        }
    
        $products = $query->get();
        return response()->json([
            'data' => $products,
        ]);
    }

    public function fetchProductDetailsById(Request $request, $id) {
        $product = Product::with([
            'category', 
            'subCategory', 
            'subSubCategory', 
            'brand', 
            'sizes', 
            'sizes.variants', 
            'gallery',
            'reviews'
        ])->find($id);

        return response()->json([
            'data' => $product
        ]);
    }
}