<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use App\Models\Product;
use App\Models\ProductCart;
use App\Models\ProductReview;
use App\Models\ProductSize;
use App\Models\ProductVariant;
use App\Models\ProductGallery;
use App\Models\ProductBrand;
use App\Models\Seller;
use App\Models\SearchTermAnalytic;
use App\Models\Wishlist;
use App\Enums\Size;
use App\Enums\Color;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Constants\Constants;
use Illuminate\Support\Facades\Storage;

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
                    'quantity' => $colorDetails['quantity'] ?? 0,
                    'is_custom' => true
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

        $route = $request->route()->getName();
        $previousUrl = URL::previous();
        $productData = $request->session()->get($this->productSessionKey, []);

        $routes = ['step1', 'step2', 'step3', 'step4'];

        $selectedColors = [];

        // $isComingFromLaterStep = $previousUrl && array_filter($routes, function($r) use ($previousUrl) {
        //     return str_contains($previousUrl, $r);
        // });

        // if (!$isComingFromLaterStep) {
        //     $request->session()->forget($this->productSessionKey);
        // }

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
        return view('forms.products.index', compact('selectedColors'));
    }

    public function storeProductCategoryDetails(Request $request)
    {
        $category = [
            'category_id' => $request->category,
            'sub_category_id' =>  $request->sub_category,
            'sub_sub_category_id' =>  $request->sub_sub_category,
            'category_term' => $request->category_term,
            'sub_category_term' =>  $request->sub_category_term,
            'sub_sub_category_term' =>  $request->sub_sub_category_term,
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
            'product_brand' => $request->product_brand ?? null,
            'brand_name' => $request->brand_name,
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
        $skuNumber = 'PRD-' . Str::upper(Str::random(12));
        DB::beginTransaction();

        if (!$productData['basic']['product_brand']) {
            $brand = ProductBrand::create([
                'name' => $productData['basic']['brand_name']
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
            'sku' => $skuNumber,
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
        DB::commit();

        $request->session()->forget($this->productSessionKey);
        return redirect()->route('products.list')->with('toast', [
            'type' => 'success',
            'message' => "Product added successfully"
        ]);
    }    

    public function getAllProductsList(Request $request) {

        $user = $request->user();
        $limit = $request->input('limit', 10);
        $search = $request->input('search');
        $categoryId = $request->input('categoryId');
        $subCategoryId = $request->input('subCategoryId');
        $subSubCategoryId = $request->input('subSubCategoryId');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        if (empty($sortBy)) {
            $sortBy = 'created_at';
        }
        
        
        $query = Product::query()->with([])->where('not_available' , '!=', true);

        if ($search) {
            $escapedQuery = str_replace(
                ['%', '_'],
                ['\%', '\_'],
                $search
            );
            $query->where(function ($q) use ($escapedQuery) {
                $q->where('title', 'like', "%{$escapedQuery}%");
            });
        }

        if (!$user->is_admin) {
            $query->where('seller_id', $user->sellerDetails->id);
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($subCategoryId) {
            $query->where('sub_category_id', $subCategoryId);
        }

        if ($subSubCategoryId) {
            $query->where('sub_sub_category_id', $subSubCategoryId);
        }

        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? strtolower($sortOrder) : 'asc';
        $query->orderBy($sortBy, $sortOrder);

        $products = $query->paginate($limit);
        return view('products.product-list', compact('products', 'limit'));
    }

    public function getProductDetailView($id)
    {
        $product = Product::findOrFail($id)->where('not_available' , '!=', true);;
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

        DB::beginTransaction();
        $brandId = null;

        // Handle brand creation/selection
        if (empty($request->product_brand)) {
            // Create new brand if none selected
            $brand = ProductBrand::create([
                'name' => $request->brand_name
            ]);
            $brandId = $brand->id;
        } else {
            // Use existing brand
            $brandId = $request->product_brand;
        }

        // Update product basic information
        $product->update([
            'title' => $request->title,
            'description' => $request->description,
            'details' => $request->details,
            'price' => (float)$request->price,
            'brand_id' => $brandId,
            'discount_percent' => (float)$request->discount_percent,
            'stock_quantity' => $request->stock_quantity,
            'category_id' => $request->category,
            'sub_category_id' => $request->sub_category,
            'sub_sub_category_id' => $request->sub_sub_category,
        ]);

        $existingSizeIds = $product->sizes()->pluck('id')->toArray();
        $updatedSizeIds = [];

        // Process each size from the request
        foreach ($sizeDetails['sizes'] as $sizeDetail) {
            // Update or create product size
            $size = ProductSize::updateOrCreate(
                [
                    'product_id' => $product->id,
                    'value' => $sizeDetail['value'],
                    'size_type' => $request->size_type
                ],
                [
                    'product_id' => $product->id,
                    'value' => $sizeDetail['value'],
                    'size_type' => $request->size_type
                ]
            );
    
            $updatedSizeIds[] = $size->id;
    
            // Get all existing variant IDs for this size
            $existingVariantIds = $size->variants()->pluck('id')->toArray();
            $updatedVariantIds = [];
    
            // Process colors/variants
            foreach ($sizeDetail['colors'] ?? [] as $color) {
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

            // Clean up variants that were removed
            if (!empty($existingVariantIds)) {
                $variantsToDelete = array_diff($existingVariantIds, $updatedVariantIds);
                if (!empty($variantsToDelete)) {
                    ProductVariant::whereIn('id', $variantsToDelete)->delete();
                }
            }
        }

        // Clean up variants that were removed
        if (!empty($existingSizeIds)) {
            $sizesToDelete = array_diff($existingSizeIds, $updatedSizeIds);
            if (!empty($sizesToDelete)) {
                ProductSize::whereIn('id', $sizesToDelete)->delete();
            }
        }

        // Handle thumbnail update
        if ($request->hasFile('default_img')) {
            if ($product->thumbnail_path && Storage::exists($product->thumbnail_path)) {
                Storage::delete($product->thumbnail_path);
            }
            $path = $request->file('default_img')->store('products/'.$product->id);
            $product->update([
                'thumbnail_path' => $path,
            ]);
        }

        // Handle product gallery images
        if ($request->images) {
            foreach ($request->file('images') as $color => $files) {
                foreach ($files as $file) {
                    $path = $file->store('products/'.$product->id);
                    
                    ProductGallery::create([
                        'product_id' => $product->id,
                        'img_path' => $path,
                        'color' => $color,
                    ]);
                }
            }
        }

        // Handle color-specific image deletions
        if ($request->colors) {
            foreach ($request->colors as $colorName => $colorData) {
                if (!empty($colorData['delete_images'])) {
                    foreach ($colorData['delete_images'] as $imageId) {
                        $galleryImage = ProductGallery::find($imageId);

                        if ($galleryImage && $galleryImage->product_id === $product->id) {
                            // Delete file and database record
                            if ($galleryImage->img_path && Storage::exists($galleryImage->img_path)) {
                                Storage::delete($galleryImage->img_path);
                            }
                            $galleryImage->delete();
                        }
                    }
                }
            }
        }

        // Get all unique colors currently in gallery
        $uniqueColors = ProductGallery::where('product_id', $product->id)
            ->whereNotNull('color')
            ->pluck('color')
            ->unique()
            ->values()
            ->toArray();

        $newColors = $request->colorsSelected;

        // Find colors that were removed
        $removedColors = array_diff($uniqueColors, $newColors);

        // Clean up images for removed colors
        if (!empty($removedColors)) {
            foreach ($removedColors as $colorName) {
                $images = ProductGallery::where('product_id', $product->id)
                    ->where('color', $colorName)
                    ->get();

                foreach ($images as $image) {
                    if ($image->img_path && Storage::exists($image->img_path)) {
                        Storage::delete($image->img_path);
                    }
                    $image->delete();
                }
            }
        }
        
        DB::commit();

        return redirect()->route('products.list')->with('toast', [
            'type' => 'success',
            'message' => "Product updated successfully"
        ]);
    }

    public function fetchProductsList(Request $request) {
        $limit = $request->input('limit', Constants::PRODUCTS_DEFAULT_LIMIT); // Default limit for products
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
        $colors = $request->input('colors');        
        
        $query = Product::query()->with([
            'category', 
            'subCategory', 
            'subSubCategory', 
            'brand', 
            'sizes', 
            'sizes.variants', 
            'gallery',
        ])->where('not_available' , '!=', true)->orderBy('created_at', 'desc');

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

        // Color filter - matches colors with supports partial matching
        if ($colors) {
            if (is_string($colors)) {
                $colors = explode(',', $colors);
            }
    
            // Normalize all input colors to lowercase
            $colors = array_map('strtolower', $colors);
    
            $query->whereHas('sizes.variants', function($q) use ($colors) {
                $q->where(function($subQuery) use ($colors) {
                    foreach ($colors as $color) {
                        $subQuery->orWhere(function($q) use ($color) {
                            $q->whereRaw([
                                '$expr' => [
                                    '$or' => [
                                        [
                                            '$eq' => [
                                                ['$toLower' => '$name'],
                                                $color
                                            ]
                                        ],
                                        [
                                            '$regexMatch' => [
                                                'input' => ['$toLower' => '$name'],
                                                'regex' => $color
                                            ]
                                        ]
                                    ]
                                ]
                            ]);
                        });
                    }
                });
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
        $products = $query->paginate($limit, ['*'], 'page', $page);    
        if ($searchTerm && strlen($searchTerm) >= 3 && $products->count() > 0) {
            $this->saveSearchTermAnalytics($searchTerm);
        }        
        return response()->json([
            'data' => $products->items(),
            'total_items' => $products->total(),
            'per_page' => $products->perPage(),
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage(),
        ]);
    }

    private function saveSearchTermAnalytics ($key) {
       $term = trim(strtolower($key));

        $record = SearchTermAnalytic::where('keyword', $term)->first();

        if ($record) {
            $record->increment('search_count');
        } else {
            SearchTermAnalytic::create([
                'keyword' => $term,
                'search_count' => 1,
            ]);
        }
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
        ])->where('not_available', '!=', true)->find($id);

        return response()->json([
            'data' => $product
        ]);
    }

    public function deleteProductItem(Request $request, $productId)
    {
        DB::beginTransaction();
        $product = Product::find($productId);
        if ($product) {
            $product->not_available = true;
            $product->save();            
        }
        ProductCart::where('product_id' , $product->id)->delete();
        Wishlist::where('product_id' , $product->id)->delete();
        DB::commit();

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => "Product deleted successfully"
        ]);
    }

    public function fetchProductsColorsList(Request $request)
    {
        $colors = ProductVariant::select('value', 'name')
            ->get()
            ->unique(function ($variant) {
                return $variant->value . '|' . $variant->name;
            })
            ->toArray();
    
        return response()->json([
            'success' => true,
            'data' => $colors
        ]);
    }

    public function fetchProductReviews(Request $request, $id)
    {
        $limit = $request->input('limit', Constants::REVIEWS_DEFAULT_LIMIT);
        $page = $request->input('page', 1);

        $query = ProductReview::where('product_id', $id)
            ->orderBy('created_at', 'desc');

        $reviews = $query->paginate($limit, ['*'], 'page', $page);            
        return response()->json([
            'data' => $reviews->items(),
            'total_items' => $reviews->total(),
            'per_page' => $reviews->perPage(),
            'current_page' => $reviews->currentPage(),
            'last_page' => $reviews->lastPage(),
        ]);
    }

    public function fetchUserProductReview(Request $request, $id)
    {
        $userId = $request->user()->id;
        $review = ProductReview::where('product_id', $id)
            ->where('user_id', $userId)
            ->first();

        return response()->json([
            'data' => $review,
        ]);
    }

    public function generateRandomProduct(Request $request, $count)
    {
         if (!is_numeric($count)) {
            return response()->json([
                'error' => 'Invalid input. Count must be a numeric value.'
            ], 422);
        }

        $count = intval($count);

        if ($count < 1 || $count > 10) {
            return response()->json([
                'error' => 'Count must be a number between 1 and 10.'
            ], 422);
        }

        $createdProducts = [];

        DB::beginTransaction();
        try {
            for ($i = 0; $i < $count; $i++) {
                // randomly select a seller 
                $sellerCount = Seller::where('status', Constants::STATUS_APPROVED)->count();

                if ($sellerCount === 0) {
                    DB::rollBack();
                    throw new \Exception('No approved sellers found');
                }

                $randomOffset = rand(0, $sellerCount - 1);

                $seller = Seller::where('status', Constants::STATUS_APPROVED)
                    ->skip($randomOffset)
                    ->first();

                // Select a random category that has at least one sub-subcategory
                $categoryCount = Category::whereHas('subCategories.subSubCategories')->count();

                if ($categoryCount === 0) {
                    DB::rollBack();
                    throw new \Exception('No categories with sub-subcategories found.');
                }

                $randomOffset = rand(0, $categoryCount - 1);

                $category = Category::whereHas('subCategories.subSubCategories')
                    ->skip($randomOffset)
                    ->first();

                // Randomly select a subcategory that has at least one sub-subcategory
                $subCategoryCount = SubCategory::where('category_id', $category->id)
                    ->whereHas('subSubCategories')->count();

                if ($subCategoryCount === 0) {
                    DB::rollBack();
                    throw new \Exception('No Sub categories with sub-subcategories found.');
                }

                $randomOffset = rand(0, $subCategoryCount - 1);

                $subCategory = SubCategory::where('category_id', $category->id)
                    ->whereHas('subSubCategories')
                    ->skip($randomOffset)
                    ->first();

                // Randomly select a sub-subcategory
                $subSubCategoryCount = SubSubCategory::where('sub_category_id', $subCategory->id)->count();

                if ($subSubCategoryCount === 0) {
                    DB::rollBack();
                    throw new \Exception('No Sub Sub categories found.');
                }

                $randomOffset = rand(0, $subSubCategoryCount - 1);

                $subSubCategory = SubSubCategory::where('sub_category_id', $subCategory->id)
                    ->skip($randomOffset)
                    ->first();

                // Randomly select a brand
                $brandCount = ProductBrand::count();

                if ($brandCount === 0) {
                    DB::rollBack();
                    throw new \Exception('No brand found.');
                }

                $randomOffset = rand(0, $brandCount - 1);

                $brand = ProductBrand::skip($randomOffset)->first();

                // Randomly select size type and sizes
                $sizeType = rand(0, 1) ? 'standard' : 'numeric';
                $availableSizes = $sizeType === 'standard' ? Size::standardSizes() : Size::numericSizes();
                shuffle($availableSizes);
                $selectedSizes = array_slice($availableSizes, 0, rand(1, 4));

                // Randomly select colors
                $availableColors = Color::values();
                shuffle($availableColors);
                $selectedColors = array_slice($availableColors, 0, rand(1, 5));

                // Generate varied product title
                $adjectives = ['Stylish', 'Premium', 'Modern', 'Classic', 'Luxury', 'Elegant', 'Trendy', 'Chic', 'Bold', 'Vibrant', 'Sleek', 'Casual', 'Comfortable'];
                $productTypes = ['Apparel', 'Gear', 'Wear', 'Essentials', 'Collection', 'Style', 'Fashion', 'Item', 'Piece', 'Design'];
                $useCases = ['for Daily Use', 'with Modern Design', 'for All Occasions', 'Limited Edition', 'with Extra Features', 'Essentials'];
                $materials = ['Cotton', 'Leather', 'Denim', 'Silk', 'Polyester', 'Wool', 'Linen', 'Suede', 'Canvas'];
                $features = ['Breathable Fabric', 'Durable Stitching', 'Water-Resistant Coating', 'Lightweight Design', 'Eco-Friendly Materials', 'Enhanced Comfort'];

                // Random pieces
                $adj = $adjectives[array_rand($adjectives)];
                $type = $productTypes[array_rand($productTypes)];
                $use = $useCases[array_rand($useCases)];
                $material = $materials[array_rand($materials)];
                $feature = $features[array_rand($features)];

                // Random title format templates
                $titlePatterns = [
                    "$adj {$subSubCategory->name} $type $use",
                    "{$brand->name} $adj {$subSubCategory->name} $type",
                    "$adj {$category->name} {$subSubCategory->name} $use",
                    "{$subCategory->name} $adj {$subSubCategory->name} $type",
                    "$adj {$subSubCategory->name} {$brand->name} $type",
                    "{$category->name} $type $use",
                    "$adj {$brand->name} {$subSubCategory->name} for {$subCategory->name}",
                    "{$brand->name} {$subSubCategory->name} $type $use",
                    "$adj {$category->name} $type $use",
                    "{$subSubCategory->name} $type by {$brand->name} $use",
                ];
                $productTitle = ucfirst($titlePatterns[array_rand($titlePatterns)]);

                // Random description format templates
                $descriptionTemplates = [
                    "High-quality {$category->name} product designed for comfort and durability.",
                    "Experience unmatched style with this {$subCategory->name} perfect for all occasions.",
                    "A must-have {$subSubCategory->name} that combines function and fashion.",
                    "Top-rated {$category->name} for those who value both design and performance.",
                    "{$adj} {$subCategory->name} that elevates your lifestyle.",
                    "Designed for modern living, this {$subSubCategory->name} stands out in every setting.",
                    "Enhance your collection with this standout {$category->name} piece.",
                    "Discover our $adj {$subSubCategory->name} $type, crafted $use.",
                    "Elevate your {$category->name} collection with this $adj {$subSubCategory->name} $type.",
                    "A $adj {$subSubCategory->name} designed $use, perfect for {$subCategory->name}.",
                    "Experience {$brand->name}'s $adj {$subSubCategory->name} $type $use.",
                    "This {$subSubCategory->name} $type offers $adj styling for {$category->name} enthusiasts.",
                    "Premium {$subSubCategory->name} $type, ideal $use.",
                    "$adj {$subCategory->name} {$subSubCategory->name} crafted for {$use}.",
                    "Our {$brand->name} {$subSubCategory->name} is a $adj $type $use.",
                    "Versatile {$category->name} {$subSubCategory->name} $type, designed $use.",
                    "Bold and $adj, this {$subSubCategory->name} $type is perfect for {$use}.",
                ];

                $productDescription = $descriptionTemplates[array_rand($descriptionTemplates)];

                // Generate varied product details
                $detailsPatterns = [
                    "Crafted from $material with $feature, ideal for {$use}.",
                    "Made with premium $material, offering $feature for {$subCategory->name}.",
                    "Features $feature using high-quality $material, perfect $use.",
                    "Designed with $material and $feature for {$subSubCategory->name} lovers.",
                    "$material construction with $feature, suitable for {$category->name} use.",
                    "Premium $material {$subSubCategory->name} with $feature $use.",
                    "Combines $material durability with $feature for {$subCategory->name}.",
                    "$feature enhances this $material {$subSubCategory->name} $type.",
                    "Built for {$use} with $material and $feature.",
                    "High-quality $material and $feature make this {$subSubCategory->name} stand out."
                ];

                $productDetails = $detailsPatterns[array_rand($detailsPatterns)];

                //image paths for sample images to be used
                $imagePaths = Storage::files('/sample_products');

                // Generate product data 
                $product = Product::create([
                    'seller_id' => $seller->id,
                    'title' => $productTitle,
                    'description' => $productDescription,
                    'details' => $productDetails,
                    'price' => rand(500, 10000),
                    'discount_percent' => rand(0, 20),
                    'delivery_days' => mt_rand(1, 9),
                    'sku' => 'PRD-' . Str::upper(Str::random(12)),                    
                    'stock_quantity' => rand(10, 100),
                    'brand_id' => $brand->id,
                    'category_id' => $category->id,
                    'sub_category_id' => $subCategory->id,
                    'sub_sub_category_id'=> $subSubCategory->id,
                    'thumbnail_path' => $imagePaths[array_rand($imagePaths)]
                ]);

                // Generate sizes and colors
                foreach ($selectedSizes as $sizeValue) {
                    $size = ProductSize::create([
                        'product_id' => $product->id,
                        'value' => $sizeValue,
                        'size_type' => $sizeType
                    ]);

                    foreach ($selectedColors as $color) {
                        ProductVariant::create([
                            'size_id' => $size->id,
                            'value' => Color::getHexCode($color),
                            'name' => $color,
                            'stock_quantity' => rand(5, 50)
                        ]);
                    }
                }

                // Generate gallery for each color
                foreach ($selectedColors as $color) {
                    shuffle($imagePaths);
                    $selectedImages = array_slice($imagePaths, 0, rand(1, 5));
                    foreach ($selectedImages as $path) {
                        ProductGallery::create([
                            'product_id' => $product->id,
                            'img_path' => $path,
                            'color' => $color,
                        ]);
                    }
                }
            }
            DB::commit();

            return response()->json([
                'message' => "$count product(s) created successfully.",
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => ['server' => 'Failed to create product: ' . $e->getMessage()]], 500);
        }
    }
}