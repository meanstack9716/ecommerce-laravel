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
use App\Models\PromoCode;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Seller;
use App\Constants\Constants;
use App\Enums\OrderStatus;

class OrderController extends Controller
{
    private function checkValidPromoCode(Promocode $promocode, $cartItems, $userId) {

        // Check if the promo code is valid (active, not expired, within max uses)
        if (!$promocode->isValid()) {
            return [
                'error' => 'Promo code is not valid or has expired.',
                'isValid' => false
            ];
        }

        // Check first-order restriction
        if ($promocode->only_first_order) {
            $userOrders = Order::where('user_id', $userId)->count();
            if ($userOrders > 0) {
                return [
                    'error' => 'Promo code is only for first order.',
                    'isValid' => false
                ];
            }
        }

        // Calculate total order amount and specific products' amount
        $totalAmount = 0;
        $specificProductsAmount = 0;
        $cartProductIds = [];

        foreach ($cartItems as $cartItem) {
            $product = Product::find($cartItem->product_id);
            if (!$product) {
                return [
                    'error' => 'Product not found: ' . $cartItem->product_id,
                    'isValid' => false
                ];
            }
            $itemTotal = $product->final_price * $cartItem->quantity;
            $totalAmount += $itemTotal;
            $cartProductIds[] = $cartItem->product_id;
            if ($promocode->applicable_to === 'specific' && !empty($promocode->applicable_products) && in_array($cartItem->product_id, $promocode->applicable_products)) {
                $specificProductsAmount += $itemTotal;
            }
        }

        // Check minimum order amount for the entire cart
        if ($promocode->min_order_amount && $totalAmount < $promocode->min_order_amount) {
            return [
                'error' => 'Order amount does not meet the minimum requirement of ' . $promocode->min_order_amount,
                'isValid' => false
            ];
        }

        // Check applicability to products
        if ($promocode->applicable_to === 'specific' && !empty($promocode->applicable_products)) {
            $applicableProductIds = $promocode->applicable_products;
            $matchingProducts = array_intersect($cartProductIds, $applicableProductIds);

            if (empty($matchingProducts)) {
                return [
                    'error' => 'Promo code is not applicable to any products in your cart',
                    'isValid' => false
                ];
            }

            // Check minimum order amount for specific products
            if ($promocode->min_order_amount && $specificProductsAmount < $promocode->min_order_amount) {
                return [
                    'error' => 'Order amount does not meet the minimum requirement of ' . $promocode->min_order_amount . ' for applicable products',
                    'isValid' => false
                ];
            }
        }

        // Check user-specific usage limit
        if ($promocode->uses_per_user) {
            $userOrderCount = Order::where('user_id', $userId)
                ->where('promocode_id', $promocode->id)
                ->count();

            if ($userOrderCount >= $promocode->uses_per_user) {
                return [
                    'error' => 'You have already used this promo code the maximum number of times',
                    'isValid' => false
                ];
            }
        }

        // Calculate discount
        $discountAmount = 0;
        $discountBaseAmount = $promocode->applicable_to === 'specific' ? $specificProductsAmount : $totalAmount;
        if ($promocode->discount_type === 'percentage') {
            $discountAmount = ($promocode->discount_value / 100) * $discountBaseAmount;
            if ($promocode->max_discount_amount && $discountAmount > $promocode->max_discount_amount) {
                $discountAmount = $promocode->max_discount_amount;
            }
        } else {
            $discountAmount = $promocode->discount_value;
        }

        return [
            'isValid' => true,
            'discount_amount' => $discountAmount,
            'total_amount' => $totalAmount,
            'specific_products_amount' => $specificProductsAmount,
        ];

    }

    public function validatePromoCode(Request $request) {
        $userId = $request->user()->id;

        $cartItems = ProductCart::where('user_id', $userId)
            ->whereIn('id', $request->cart_items_ids)
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['errors' => ['cart' => 'Your cart is empty']], 422);
        }

        $promocode = PromoCode::where('code', $request->promo_code)->first();

        if (!$promocode) {
            return response()->json(['errors' => ['promo_code' => 'Invalid promo code']], 422);
        }

        try {
            $result = $this->checkValidPromoCode($promocode, $cartItems, $userId);
            if ($result['isValid']) {
                return response()->json([
                    'message' => 'Promo code is valid and can be applied',
                    'promo_code' => $promocode->code,
                    'discount_amount' => $result['discount_amount'],
                    'total_amount' => $result['total_amount'],
                    'discounted_amount' => $result['total_amount'] - $result['discount_amount'],
                ], 200);
            } else {
                return response()->json(['errors' => ['promo_code' => $result['error']]], 422);
            }
        } catch (\Exception $e) {
            return response()->json(['errors' => ['promo_code' => $e->getMessage()]], 422);
        }
    }

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
                    'contact_number' => $shippingAddress->contact_number ?? $request->user()->phone_number,
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
            return response()->json(['errors' => ['server' => 'Failed to create order: ' . $e->getMessage()]], 500);
        }
    }

    public function fetchAllOrderItems(Request $request) {
        $limit = $request->input('limit');
        $page = $request->input('page', 1);
        $status = $request->input('status');
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');

        $query = Order::with(['items', 'items.product', 'items.product.sizes', 'items.product.sizes.variants', 'items.product.reviews' ])
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

        $orders->each(function ($order) {
            $order->items->each(function ($item) {
                if ($item->product && $item->selected_color_name) {
                    $item->product->setRelation('gallery', $item->product->galleryForColor($item->selected_color_name)->get());
                } else {
                    $item->product->setRelation('gallery', collect([]));
                }
            });
        });
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

    public function fetchOrderDetailsById(Request $request, $orderId)
    {
        $order = Order::with([
            'items',
            'items.product',
            'items.product.sizes',
            'items.product.brand',
            'items.product.sizes.variants',
            'items.product.reviews'
        ])->find($orderId);

        if (!$order) {
            return response()->json([
                'status' => 'error',
                'message' => 'Order not found'
            ], 404);
        }

        $order->items->each(function ($item) {
            if ($item->product && $item->selected_color_name) {
                $item->product->setRelation('gallery', $item->product->galleryForColor($item->selected_color_name)->get());
            } else {
                $item->product->setRelation('gallery', collect([]));
            }
        });

        return response()->json([
            'status' => 'success',
            'data' => $order
        ], 200);
    }

    public function getAllOrdersList(Request $request) {
        $user = $request->user();
        $limit = $request->input('limit', 10);
        $status = $request->input('status');
        $sellerId = $request->input('sellerId');
        $sellers =  Seller::where('status', Constants::STATUS_APPROVED )->get();

        $query = Order::query()->with(['items', 'items.product'])->orderBy('created_at', 'desc');

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