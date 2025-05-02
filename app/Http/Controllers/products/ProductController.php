<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\SubSubCategory;

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

        $routes = ['step1', 'step2'];

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
}