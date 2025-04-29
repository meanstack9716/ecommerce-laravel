<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ProductType;

class CategoryController extends Controller
{
    public function showAddCategoryForm(Request $request)
    {
        return view('categories.add-new-category');
    }

    public function addNewCategory(Request $request)
    {
        $img_path = $request->file('category_img')->store('categories');

        $category = Category::create([
            'name' => $request->category_name,
            'description' => $request->category_description,
            'img_path' => $img_path,
        ]);
        return redirect()->route('category.list');
    }

    public function getAllCategoriesList(Request $request) {

        $limit = $request->input('limit', 10);
        $search = $request->input('search');

        $query = Category::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $categories = $query->paginate($limit);
        return view('categories.list', compact('categories', 'limit'));
    }

    public function showSubCategoryForm(Request $request)
    {
        $categories = Category::all();
        return view('categories.sub-categories.form', compact('categories'));
    }

    public function addNewSubCategory(Request $request)
    {
        $img_path = $request->file('category_img')->store('sub_categories');

        $category = SubCategory::create([
            'name' => $request->category_name,
            'description' => $request->category_description,
            'img_path' => $img_path,
            'category_id' => $request->category_type
        ]);
        return redirect()->route('sub-category.list');
    }

    public function getAllSubCategoriesList(Request $request) {

        $limit = $request->input('limit', 10);
        $search = $request->input('search');
        $categoryId = $request->input('categoryId');
        
        $query = SubCategory::query()->with(['category']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }


        $subCategories = $query->paginate($limit);
        $categories = Category::all();
        return view('categories.sub-categories.list', compact('categories', 'limit', 'subCategories'));

    }

    public function getSubcategories(Request $request)
    {
        $categoryId = $request->input('category_id');
        $subcategories = SubCategory::where('category_id', $categoryId)->get();
    
        return response()->json($subcategories);
    }

    public function showProductTypeForm(Request $request)
    {
        $categories = Category::all();
        $subCategories = collect();
    
        if (old('category')) {
            $subCategories = SubCategory::where('category_id', old('category'))->get();
        }
        return view('categories.product-type.form', compact('categories', 'subCategories'));
    }

    public function addNewProductType(Request $request)
    {
        $img_path = $request->file('img')->store('product_type');

        $category = ProductType::create([
            'name' => $request->name,
            'description' => $request->description,
            'img_path' => $img_path,
            'category_id' => $request->category,
            'sub_category_id' => $request->sub_category
        ]);
        return redirect()->route('product-type.list');
    }

    public function getAllProductTypesList(Request $request) {

        $limit = $request->input('limit', 10);
        $search = $request->input('search');
        $categoryId = $request->input('categoryId');
        
        $query = ProductType::query()->with(['subCategory']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $productTypes = $query->paginate($limit);
        $categories = Category::all();
        // return response()->json([
        //     'message' => 'You email has been verified successfully',
        //     'ddd' => $productTypes
        // ]);
        return view('categories.product-type.list', compact('productTypes','categories', 'limit'));
    }
}