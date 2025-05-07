<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductBrand;

class ProductBrandController extends Controller
{
    public function showAddProductBrandForm()
    {
        return view('products.brands.form');
    }

    public function addNewBrand(Request $request)
    {
        $img_path = $request->file('img')->store('brands');

        ProductBrand::create([
            'name' => $request->name,
            'description' => $request->description,
            'img_path' => $img_path,
        ]);
        return redirect()->route('products.brand.list');
    }

    public function getAllProductBrandList(Request $request) {

        $limit = $request->input('limit', 10);
        $search = $request->input('search');

        $query = ProductBrand::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $query->orderBy('name', 'asc');

        $brands = $query->paginate($limit);
        return view('products.brands.list', compact('brands', 'limit'));
    }
}