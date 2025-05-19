<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\SubSubCategory;

class CategoryController extends Controller
{
    public function showAddCategoryForm(Request $request)
    {
        return view('categories.category-form');
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

    public function editCategoryDetails(Request $request, $categoryId)
    {
        $category = Category::findOrFail($categoryId);        
        return view('categories.category-form', compact('category'));
    }

    public function updateCategory(Request $request, $categoryId)
    {
        $category = Category::findOrFail($categoryId);

        $category->name = $request->category_name;
        $category->description = $request->category_description;

        if ($request->hasFile('category_img')) {
            if ($category->img_path) {
                Storage::delete('public/' . $category->img_path);
            }
            $img_path = $request->file('category_img')->store('categories');
        
            $category->img_path = $img_path;
        }

        $category->save();

        return redirect()->route('category.list')
            ->with('success', 'Category updated successfully!');
    }

    public function showSubCategoryForm(Request $request)
    {
        $categories = Category::all();
        return view('categories.sub-categories.form', compact('categories'));
    }

    public function addNewSubCategory(Request $request)
    {
        $img_path = $request->file('category_img')->store('sub_categories/'. $request->category_type);

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

    public function editSubCategoryDetails(Request $request, $categoryId)
    {
        $subCategory = SubCategory::findOrFail($categoryId);
        $categories = Category::all();      
        return view('categories.sub-categories.form', compact('subCategory', 'categories'));
    }

    public function updateSubCategory(Request $request, $categoryId)
    {
        $subCategory = SubCategory::findOrFail($categoryId);

        $subCategory->name = $request->category_name;
        $subCategory->description = $request->category_description;
        $subCategory->category_id = $request->category_type;

        if ($request->hasFile('category_img')) {
            if ($subCategory->img_path) {
                Storage::delete('public/' . $subCategory->img_path);
            }
            $img_path = $request->file('category_img')->store('sub_categories/'. $request->category_type);
        
            $subCategory->img_path = $img_path;
        }

        $subCategory->save();

        return redirect()->route('sub-category.list')
            ->with('success', 'Sub Category updated successfully!');
    }

    public function getSubcategories(Request $request)
    {
        $categoryId = $request->input('category_id');
        $subcategories = SubCategory::where('category_id', $categoryId)->get();
    
        return response()->json($subcategories);
    }

    public function  getSubSubcategories(Request $request)
    {
        $categoryId = $request->input('category_id');
        $subCategoryId = $request->input('sub_category_id');

        $query = SubSubCategory::query();

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($subCategoryId) {
            $query->where('sub_category_id', $subCategoryId);
        }

        $sub = $query->get();
    
        return response()->json($sub);
    }

    public function showSubSubCategoryForm(Request $request)
    {
        $categories = Category::all();
        $subCategories = collect();
    
        if (old('category')) {
            $subCategories = SubCategory::where('category_id', old('category'))->get();
        }
        return view('categories.sub-sub-categories.form', compact('categories', 'subCategories'));
    }

    public function addNewSubSubCategory(Request $request)
    {
        $img_path = $request->file('img')->store('sub_sub_categories/'. $request->sub_category);

        $category = SubSubCategory::create([
            'name' => $request->name,
            'description' => $request->description,
            'img_path' => $img_path,
            'category_id' => $request->category,
            'sub_category_id' => $request->sub_category
        ]);
        return redirect()->route('sub-sub-category.list');
    }

    public function editSubSubCategoryDetails(Request $request, $categoryId)
    {
        $subSubCategory = SubSubCategory::findOrFail($categoryId);
        $categories = Category::all();
        $subCategories = collect();
    
        if (old('category')) {
            $subCategories = SubCategory::where('category_id', old('category'))->get();
        } else {
            $subCategories = SubCategory::where('category_id', $subSubCategory->category_id)->get();
        }
        return view('categories.sub-sub-categories.form', compact('subSubCategory', 'categories', 'subCategories'));
    }

    public function updateSubSubCategory(Request $request, $categoryId)
    {
        $subSubCategory = SubSubCategory::findOrFail($categoryId);

        $subSubCategory->name = $request->name;
        $subSubCategory->description = $request->description;
        $subSubCategory->category_id = $request->category;
        $subSubCategory->sub_category_id = $request->sub_category;

        if ($request->hasFile('img')) {
            if ($subSubCategory->img_path) {
                Storage::delete('public/' . $subSubCategory->img_path);
            }
            $img_path = $request->file('img')->store('sub_sub_categories/'. $request->sub_category);
        
            $subSubCategory->img_path = $img_path;
        }

        $subSubCategory->save();

        return redirect()->route('sub-sub-category.list')
            ->with('success', 'Sub Sub Category updated successfully!');
    }

    public function getAllSubSubCategoryList(Request $request) {

        $limit = $request->input('limit', 10);
        $search = $request->input('search');
        $categoryId = $request->input('categoryId');
        
        $query = SubSubCategory::query()->with(['subCategory']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $subSubCategories = $query->paginate($limit);
        $categories = Category::all();
        return view('categories.sub-sub-categories.list', compact('subSubCategories','categories', 'limit'));
    }

    public function fetchCategoryList(Request $request) {

        $limit = $request->input('limit');
        $page = $request->input('page', 1);
        $searchTerm = $request->input('searchTerm');
        $query = Category::query()->with(['subCategories', 'subCategories.subSubCategories']);

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%");
            });
        }

        if ($limit) {
            $categories = $query->paginate($limit, ['*'], 'page', $page);            
            return response()->json([
                'data' => $categories->items()
            ]);
        }

        $categories = $query->get();
        return response()->json([
            'data' => $categories
        ]);
    }

    public function fetchCategoryById(Request $request, $id) {
        $category = Category::with(['subCategories', 'subCategories.subSubCategories'])->find($id);

        return response()->json([
            'data' => $category
        ]);
    }

    public function fetchSubCategoryList(Request $request) {

        $limit = $request->input('limit');
        $page = $request->input('page', 1);
        $searchTerm = $request->input('searchTerm');
        $categoryId = $request->input('categoryId');
        $query = SubCategory::query()->with(['category', 'subSubCategories']);

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($limit) {
            $categories = $query->paginate($limit, ['*'], 'page', $page);            
            return response()->json([
                'data' => $categories->items()
            ]);
        }

        $categories = $query->get();
        return response()->json([
            'data' => $categories
        ]);
    }

    public function fetchSubCategoryById(Request $request, $id) {
        $subCategory = SubCategory::with(['category', 'subSubCategories'])->find($id);

        return response()->json([
            'data' => $subCategory
        ]);
    }

    public function fetchSubSubCategoryList(Request $request) {

        $limit = $request->input('limit');
        $page = $request->input('page', 1);
        $searchTerm = $request->input('searchTerm');
        $categoryId = $request->input('categoryId');
        $subCategoryId = $request->input('subCategoryId');
        $query = SubSubCategory::query()->with(['category', 'subCategory']);

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($subCategoryId) {
            $query->where('sub_category_id', $subCategoryId);
        }

        if ($limit) {
            $cat = $query->paginate($limit, ['*'], 'page', $page);            
            return response()->json([
                'data' => $cat->items()
            ]);
        }

        $cat = $query->get();
        return response()->json([
            'data' => $cat
        ]);
    }

    public function fetchSubSubCategoryById(Request $request, $id) {
        $cat = SubSubCategory::with(['category', 'subCategory'])->find($id);

        return response()->json([
            'data' => $cat
        ]);
    }
}