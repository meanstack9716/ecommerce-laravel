<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\ProductBrand;
use App\Models\Product;

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

    public function editBrandDetails(Request $request, $brandId)
    {
        $brand = ProductBrand::findOrFail($brandId);        
        return view('products.brands.form', compact('brand'));
    }

    public function updateBrand(Request $request, $brandId)
    {
        $brand = ProductBrand::findOrFail($brandId);

        $brand->name = $request->name;
        $brand->description = $request->description;

        if ($request->hasFile('img')) {
            if ($brand->img_path && Storage::exists($brand->img_path)) {
                Storage::delete($brand->img_path);
            }
            $img_path = $request->file('img')->store('brands');
        
            $brand->img_path = $img_path;
        }

        $brand->save();

        return redirect()->route('products.brand.list')
            ->with('success', 'Category updated successfully!');
    }

    public function deleteBrand (Request $request, $brandId)
    {
        $brand = ProductBrand::find($brandId);
        if (!$brand) {
            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => 'Product brand not found.'
            ]);
        }

        $productCount = Product::where('brand_id', $brandId)->count();

        if ($productCount > 0) {
            return redirect()->back()->with('toast', [
                'type' => 'error',
                'message' => "Cannot delete brand because it is associated with $productCount product(s)."
            ]);
        }
        $brand->delete();

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => "Brand deleted successfully"
        ]);
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