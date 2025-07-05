<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\ProductVariant;
use App\Models\ProductCart;
use App\Models\Wishlist;

class ProductWishlistController extends Controller
{
    public function addProductToWish(Request $request)
    {
        $productSize = ProductSize::where('product_id', $request->product_id)
            ->where('value', 'like', $request->selected_size)
            ->first();

        $product = Product::with([])->find($request->product_id);

        if (!$productSize) {
            return response()->json(['errors' => [
                'selected_size' => 'The size selected for this product is not available'
            ]], 422);
        }

        $productVariant = ProductVariant::where('size_id', $productSize->id)
            ->where('value', $request->selected_color) 
            ->first();

        if (!$productVariant) {
            return response()->json(['errors' => [
                'selected_color' => 'The color selected for this product is not available'
            ]], 422);
        }

        $existingItem = Wishlist::where('user_id', $request->user()->id)
            ->where('product_id', $request->product_id)
            ->where('selected_size', $productSize->value)
            ->where('selected_color', $request->selected_color)
            ->first();

        $newQuantity = $existingItem ? $existingItem->quantity + $request->quantity : $request->quantity;

        if($productVariant->stock_quantity < $newQuantity) {
            return response()->json(['errors' => [
                'quantity' => 'The requested quantity is not available in stock'
            ]], 422);
        }

        if ($existingItem) {
            $existingItem->update([
                'quantity' => $newQuantity
            ]);
        } else {
            Wishlist::create([
                'user_id' => $request->user()->id,
                'product_id' => $request->product_id,
                'selected_size' => $productSize->value,
                'selected_color' => $request->selected_color,
                'selected_color_name' => $productVariant->name,
                'quantity' => (float)$request->quantity
            ]);
        }

        return response()->json([
            'message' => 'Product Successfully added to wishlist.'
        ]);
    }

    public function fetchWishlist(Request $request)
    {
        $userId = $request->user()->id;

        $items = Wishlist::with([
            'product.sizes',
            'product.sizes.variants',
            'product.gallery',
        ])->where('user_id', $userId)
        ->get();

        return response()->json([
            'data' => $items
        ]);
    }

    public function removeItemsFromWishList(Request $request)
    {
        $userId = $request->user()->id;

        $deletedCount = Wishlist::whereIn('id', $request->item_ids)
            ->where('user_id', $userId)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => "{$deletedCount} item(s) removed from the wishlist.",
        ]);
    }

    public function removeAllWishlistItems(Request $request)
    {
        $userId = $request->user()->id;

        $deletedCount = Wishlist::where('user_id', $userId)->delete();

        return response()->json([
            'success' => true,
            'message' => "{$deletedCount} item(s) removed from your wishlist.",
        ]);
    }

    public function moveItemToCart(Request $request)
    {
        $userId = $request->user()->id;
        $itemIds = $request->input('item_ids', []);

        $items = Wishlist::whereIn('id', $itemIds)
            ->where('user_id', $userId)
            ->get();
        
        if ($items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No matching wishlist items found for the user.',
            ], 404);
        }
        
        DB::beginTransaction();

        foreach ($items as $item) {

            $productSize = ProductSize::where('product_id', $item->product_id)
                ->where('value', 'like', $item->selected_size)
                ->first();

            if (!$productSize) {
                DB::rollBack();
                return response()->json(['errors' => [
                    'product' => 'The size selected is not available for product'. $item->product->title
                ]], 422);
            }

            $productVariant = ProductVariant::where('size_id', $productSize->id)
                ->where('value', $item->selected_color) 
                ->first();

            if (!$productVariant) {
                DB::rollBack();
                return response()->json(['errors' => [
                    'product' => 'The color selected is not available for product'. $item->product->title
                ]], 422);
            }

            $existingCartItem = ProductCart::where('user_id', $request->user()->id)
                ->where('product_id', $item->product_id)
                ->where('selected_size', $productSize->value)
                ->where('selected_color', $item->selected_color)
                ->first();

            $newQuantity = $existingCartItem ? $existingCartItem->quantity + $item->quantity : $item->quantity;

            if($productVariant->stock_quantity < $newQuantity) {
                DB::rollBack();
                return response()->json(['errors' => [
                    'product' => 'The quantity selected is not available for product'. $item->product->title
                ]], 422);
            }

            if ($existingCartItem) {
                $existingCartItem->update([
                    'quantity' => $newQuantity
                ]);
            } else {
                ProductCart::create([
                    'user_id' => $userId,
                    'product_id' => $item->product_id,
                    'selected_size' => $productSize->value,
                    'selected_color' => $item->selected_color,
                    'selected_color_name' => $productVariant->name,
                    'quantity' => (float)$item->quantity
                ]);
            }
                        
            $item->delete();
        }
        DB::commit();

        return response()->json([
            'success' => true,
            'message' => "Items moved to cart.",
        ]);
    }

}