<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Seller;
use Illuminate\Http\Request;
use App\Constants\Constants;

class SearchController extends Controller
{
    public function searchSellers(Request $request)
    {
        $query = $request->input('searchTerm');
    
        $sellers = Seller::query()
            ->where('business_name', 'like', "%{$query}%")
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
    
        $data = Category::query()
            ->where('name', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'name']);

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }
}