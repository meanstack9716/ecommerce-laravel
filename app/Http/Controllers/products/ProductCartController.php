<?php

namespace App\Http\Controllers\Products;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\ProductVariant;
use App\Models\ProductCart;
use App\Models\Wishlist;

class ProductCartController extends Controller
{
    public function addProductToCart(Request $request)
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

        $existingCartItem = ProductCart::where('user_id', $request->user()->id)
            ->where('product_id', $request->product_id)
            ->where('selected_size', $productSize->value)
            ->where('selected_color', $request->selected_color)
            ->first();

        $newQuantity = $existingCartItem ? $existingCartItem->quantity + $request->quantity : $request->quantity;

        if($productVariant->stock_quantity < $newQuantity) {
            return response()->json(['errors' => [
                'quantity' => 'The requested quantity is not available in stock'
            ]], 422);
        }

        if ($existingCartItem) {
            $existingCartItem->update([
                'quantity' => $newQuantity
            ]);
        } else {
            ProductCart::create([
                'user_id' => $request->user()->id,
                'product_id' => $request->product_id,
                'selected_size' => $productSize->value,
                'selected_color' => $request->selected_color,
                'selected_color_name' => $productVariant->name,
                'quantity' => (float)$request->quantity
            ]);
        }

        return response()->json([
            'message' => 'Product Successfully added to cart.'
        ]);
    }

    public function fetchCartItemList(Request $request)
    {
        $userId = $request->user()->id;

        $cartItems = ProductCart::with([
            'product.sizes',
            'product.sizes.variants',
            'product.gallery',
        ])->where('user_id', $userId)
        ->get();

        return response()->json([
            'data' => $cartItems
        ]);
    }

    public function removeCartItems(Request $request)
    {
        $userId = $request->user()->id;

        $deletedCount = ProductCart::whereIn('id', $request->item_ids)
            ->where('user_id', $userId)
            ->delete();

        return response()->json([
            'success' => true,
            'message' => "{$deletedCount} item(s) removed from the cart.",
        ]);
    }

    public function removeAllCartItems(Request $request)
    {
        $userId = $request->user()->id;

        $deletedCount = ProductCart::where('user_id', $userId)->delete();

        return response()->json([
            'success' => true,
            'message' => "{$deletedCount} item(s) removed from your cart.",
        ]);
    }

    public function moveItemToWishlist(Request $request)
    {
        $userId = $request->user()->id;
        $itemIds = $request->input('item_ids', []);

        $cartItems = ProductCart::whereIn('id', $itemIds)
            ->where('user_id', $userId)
            ->get();
        
        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No matching cart items found for the user.',
            ], 404);
        }
        
        foreach ($cartItems as $cartItem) {

            $existingWishlistItem = Wishlist::where('user_id', $userId)
                ->where('product_id', $cartItem->product_id)
                ->where('selected_color', $cartItem->selected_color)
                ->where('selected_size', $cartItem->selected_size)
                ->first();

            if ($existingWishlistItem) {
                $skippedItems[] = $cartItem->id;
                $cartItem->delete();
                continue;
            }

            Wishlist::create([
                'user_id' => $userId,
                'product_id' => $cartItem->product_id,
                'selected_color' => $cartItem->selected_color,
                'selected_color_name' => $cartItem->selected_color_name,
                'selected_size' => $cartItem->selected_size,
                'quantity' => $cartItem->quantity,
            ]);
                        
            $cartItem->delete();
        }

        return response()->json([
            'success' => true,
            'message' => "Items moved to wishlist.",
        ]);
    }

    public function updateCartProduct(Request $request)
    {
        $userId = $request->user()->id;

        $cartItem = ProductCart::where('id', $request->id)
            ->where('user_id', $userId)
            ->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found.',
            ], 404);
        }

        $updateData = [];
        $errors = [];

        // Check if size is being updated
        if ($request->has('size')) {
            $productSize = ProductSize::where('product_id', $cartItem->product_id)
                ->where('value', 'like', $request->size)
                ->first();

            if (!$productSize) {
                $errors['size'] = 'The size selected for this product is not available';
            } else {
                // Check if current color exists for the new size
                $productVariant = ProductVariant::where('size_id', $productSize->id)
                    ->where('value', $request->has('color') ? $request->color : $cartItem->selected_color)
                    ->first();

                if (!$productVariant) {
                    $errors['color'] = 'The current color is not available for the selected size';
                } else {
                    $updateData['selected_size'] = $productSize->value;
                    // Only update color if it was explicitly provided
                    if ($request->has('color')) {
                        $updateData['selected_color'] = $request->color;
                        $updateData['selected_color_name'] = $productVariant->name;
                    }
                }
            }
        }

        // Handle color update separately if size wasn't updated
        if ($request->has('color') && !$request->has('size')) {
            $productSize = ProductSize::where('product_id', $cartItem->product_id)
                ->where('value', 'like', $cartItem->selected_size)
                ->first();

            $productVariant = ProductVariant::where('size_id', $productSize->id)
                ->where('value', $request->color)
                ->first();

            if (!$productVariant) {
                $errors['color'] = 'The color selected is not available for this size';
            } else {
                $updateData['selected_color'] = $request->color;
                $updateData['selected_color_name'] = $productVariant->name;
            }
        }

        // Check if quantity is being updated
        if ($request->has('quantity')) {
            // Determine which product variant to check stock against
            $sizeToCheck = $request->has('size') ? $request->size : $cartItem->selected_size;
            $colorToCheck = $request->has('color') ? $request->color : $cartItem->selected_color;
        
            $productSize = ProductSize::where('product_id', $cartItem->product_id)
                ->where('value', 'like', $sizeToCheck)
                ->first();

            $productVariant = ProductVariant::where('size_id', $productSize->id)
                ->where('value', $colorToCheck)
                ->first();

            if ($productVariant->stock_quantity < $request->quantity) {
                $errors['quantity'] = 'The requested quantity is not available in stock';
            } else {
                $updateData['quantity'] = $request->quantity;
            }
        }

        // Return errors if any
        if (!empty($errors)) {
            return response()->json(['errors' => $errors], 422);
        }

        // Update the cart item
        $cartItem->update($updateData);

        return response()->json([
            'message' => 'Cart item updated successfully',
            'data' => $cartItem->fresh()
        ]);
    }

}