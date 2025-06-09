<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\SubSubCategory;
use App\Models\ProductBrand;
use App\Models\SearchTermAnalytic;
use App\Models\Seller;
use Illuminate\Http\Request;
use App\Constants\Constants;

class SearchController extends Controller
{
    public function searchSellers(Request $request)
    {
        $query = $request->input('searchTerm');

        $escapedQuery = str_replace(
            ['%', '_'], 
            ['\%', '\_'], 
            $query
        );
    
        $sellers = Seller::query()
            ->where('business_name', 'like','%' . $escapedQuery . '%')
            ->where('status', Constants::STATUS_APPROVED )
            ->limit(10)
            ->get(['id', 'business_name']);

        return response()->json([
            'status' => 'success',
            'data' => $sellers
        ], 200);
    }

    public function searchCategories(Request $request)
    {
        $query = $request->input('searchTerm');

        $escapedQuery = str_replace(
            ['%', '_'], 
            ['\%', '\_'], 
            $query
        );
    
        $data = Category::query()
            ->where('name', 'like', '%' . $escapedQuery . '%')
            ->limit(10)
            ->get(['id', 'name']);

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    public function searchSubCategories(Request $request)
    {
        $categoryId = $request->input('categoryId');
        $searchTerm = $request->input('searchTerm');
    
        $query = SubCategory::query();

        if ($searchTerm) {
            $escapedQuery = str_replace(
                ['%', '_'],
                ['\%', '\_'],
                $searchTerm
            );
            $query->where('name', 'like', '%' . $escapedQuery . '%');
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        $data = $query->limit(10)->get(['id', 'name']);

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    public function searchSubSubCategories(Request $request)
    {
        $subCategoryId = $request->input('subCategoryId');
        $categoryId = $request->input('categoryId');
        $searchTerm = $request->input('searchTerm');
    
        $query = SubSubCategory::query();

        if ($searchTerm) {
            $escapedQuery = str_replace(
                ['%', '_'],
                ['\%', '\_'],
                $searchTerm
            );
            $query->where('name', 'like', '%' . $escapedQuery . '%');
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        if ($subCategoryId) {
            $query->where('sub_category_id', $subCategoryId);
        }

        $data = $query->limit(10)->get(['id', 'name']);

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    public function searchBrands(Request $request)
    {
        $query = $request->input('searchTerm');

        $escapedQuery = str_replace(
            ['%', '_'], 
            ['\%', '\_'], 
            $query
        );
    
        $data = ProductBrand::query()
            ->where('name', 'like','%' . $escapedQuery . '%')
            ->limit(10)
            ->get(['id', 'name']);

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    public function getRecommendedKeywords(Request $request)
    {
        $limit = $request->input('limit', 10);

        $keywords = SearchTermAnalytic::orderBy('search_count', 'desc')
            ->limit($limit)
            ->pluck('keyword');

        return response()->json([
            'data' => $keywords,
        ]);
    }

}