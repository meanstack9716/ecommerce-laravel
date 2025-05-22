<?php

namespace App\Http\Controllers\Orders;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Product;
use App\Models\ProductSize;
use App\Models\ProductCart;
use App\Models\ProductReview;
use App\Models\ProductVariant;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Seller;
use App\Constants\Constants;
use App\Enums\OrderStatus;

class OrderController extends Controller
{
    public function createNewOrder(Request $request) {
        
        $userId = $request->user()->id;
        $shippingAddress = Address::where('id', $request->shipping_address_id)->where('user_id', $userId)->first();

        if (!$shippingAddress) {
            return response()->json(['errors' => [
                'shipping_address_id' => 'The shipping Address is wrong'
            ]], 422);
        }

        $shippingAddressString = $shippingAddress->line1.", ". $shippingAddress->line2. ", ". 
            $shippingAddress->city. ", " .$shippingAddress->state. ", " .$shippingAddress->country. " - " .$shippingAddress->postal_code;

        $cartItems = ProductCart::where('user_id', $userId)
            ->whereIn('id', $request->cart_items_ids)
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['errors' => ['cart' => 'Your cart is empty']], 422);
        }

        $groupedBySeller = $cartItems->groupBy(function ($item) {
            return $item->product->seller_id;
        });

        DB::beginTransaction();
        try {
            foreach ($groupedBySeller as $sellerId => $selectedItems) {
                $totalAmount = 0;
                $orderNumber = 'ORD-' . Str::upper(Str::random(12));

                $order = Order::create([
                    'user_id' => $userId,
                    'seller_id' => $sellerId,
                    'order_number' => $orderNumber,
                    'total_amount' => 0,
                    'status' => Constants::STATUS_PENDING,
                    'shipping_address' => $shippingAddressString,
                    'shipping_address_type' => $shippingAddress->type,
                    'contact_name' => $shippingAddress->contact_name ?? $request->user()->first_name,
                    'contact_mobile' => $shippingAddress->contact_mobile ?? $request->user()->phone_number,
                    'payment_method' => $request->payment_method,
                    'payment_status' => Constants::STATUS_PENDING,
                    'order_note' => $request->order_note
                ]);

                foreach ($selectedItems as $cartItem) {
                    $product = Product::find($cartItem->product_id);

                    if (!$product) {
                        DB::rollBack();
                        return response()->json(['errors' => ['product' => 'Product not found']], 422);
                    }

                    $productSize = ProductSize::where('product_id', $cartItem->product_id)
                        ->where('value', $cartItem->selected_size)
                        ->first();

                    if (!$productSize) {
                        DB::rollBack();
                        return response()->json(['errors' => [
                            'product' => 'The size selected is not available for this product: '. $product->title
                        ]], 422);
                    }

                    $productVariant = ProductVariant::where('size_id', $productSize->id)
                        ->where('value', $cartItem->selected_color) 
                        ->first();

                    if (!$productVariant) {
                        DB::rollBack();
                        return response()->json(['errors' => [
                            'product' => 'The color selected is not available for this product: '. $product->title
                        ]], 422);
                    }

                    if($productVariant->stock_quantity < $cartItem->quantity) {
                        DB::rollBack();
                        return response()->json(['errors' => [
                            'product' => 'The requested quantity is not available in stock for the product: '. $product->title
                        ]], 422);
                    }

                    $totalAmount += $product->final_price * $cartItem->quantity;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $cartItem->product_id,
                        'selected_size' => $cartItem->selected_size,
                        'selected_color' => $cartItem->selected_color,
                        'selected_color_name' => $cartItem->selected_color_name,
                        'quantity' => (float)$cartItem->quantity,
                        'price' => $product->price,
                        'discount_percent' => $product->discount_percent,
                        'final_price' => $product->final_price
                    ]);

                    $newquantity = $productVariant->stock_quantity - $cartItem->quantity;
                    $productVariant->update(['stock_quantity' => $newquantity]);
                }
                $order->update(['total_amount' => $totalAmount]);
            }

            ProductCart::where('user_id', $userId)
                ->whereIn('id', $request->cart_items_ids)->delete();

            DB::commit();
            return response()->json([
                'message' => 'Order created successfully',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => ['server' => 'Failed to create order']], 500);
        }
    }

    public function fetchAllOrderItems(Request $request) {
        $limit = $request->input('limit');
        $page = $request->input('page', 1);
        $status = $request->input('status');
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');

        $query = Order::with(['items', 'items.product', 'items.product.sizes', 'items.product.sizes.variants', 'items.product.gallery'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc');
        
        if ($status) {
            $query->where('status', $status);
        }

        if ($fromDate) {
            $query->whereDate('created_at', '>=', $fromDate);
        }
    
        if ($toDate) {
            $query->whereDate('created_at', '<=', $toDate);
        }

        if ($limit) {
            $orders = $query->paginate($limit, ['*'], 'page', $page);            
            return response()->json([
                'data' => $orders->items(),
            ]);
        }
    
        $orders = $query->get();
        return response()->json([
            'data' => $orders,
        ]);
    }

    public function fetchOrderStatusesList()
    {
        return response()->json([
            'data' => OrderStatus::values()
        ]);
    }

    public function fetchOrderDetailsById(Request $request, $orderId) {
        $order = Order::with([
            'items', 
            'items.product', 
            'items.product.sizes',
            'items.product.brand',
            'items.product.sizes.variants', 
            'items.product.gallery',
            'items.product.reviews'
        ])->find($orderId);

        return response()->json([
            'data' => $order
        ]);
    }

    public function getAllOrdersList(Request $request) {
        $user = $request->user();
        $limit = $request->input('limit', 10);
        $status = $request->input('status');
        $sellerId = $request->input('sellerId');
        $sellers =  Seller::where('status', Constants::STATUS_APPROVED )->get();

        $query = Order::query()->with(['items', 'items.product']);

        if (!$user->is_admin) {
            $query->where('seller_id', $user->sellerDetails->id);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if($sellerId) {
            $query->where('seller_id', $sellerId);
        }

        $orders = $query->paginate($limit);
        return view('order.list', compact('orders', 'limit', 'sellers'));
    }

    public function updateOrderStatus(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        $order->update(['status' => $request->status]);
        
        return back()->with('success', 'Order status updated successfully');
    }

    public function getOrderDetails(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);        
        return view('order.details', compact('order'));
    }

    public function createProductReview(Request $request)
    {
        $user = $request->user();
        $productId = $request->product_id;

        $hasPurchased = Order::where('user_id', $user->id)
            ->where('status', Constants::STATUS_DELIVERED)  
            ->whereHas('items', function($q) use ($productId) {
                $q->where('product_id', $productId);
            })->exists();


        if (!$hasPurchased) {
            return response()->json([
                'errors' => [
                    'product' => 'You can only review products you have purchased'
                ]
            ], 403);
        }

        $existingReview = ProductReview::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($existingReview) {
            return response()->json([
                'errors' => [
                    'product' => 'You have already reviewed this product'
                ]
            ], 403);
        }

        $order = Order::where('user_id', $user->id)
            ->whereHas('items', function($q) use ($productId) {
                $q->where('product_id', $productId);
            })->latest()->first();

        $review = ProductReview::create([
            'user_id' => $user->id,
            'product_id' => $productId,
            'order_id' => $order->id,
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return response()->json([
            'message' => 'Review submitted successfully',
            'review' => $review
        ], 201);
    }
}