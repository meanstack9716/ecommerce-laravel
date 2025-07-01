<?php

namespace App\Http\Controllers\Orders;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ReturnRequest;
use App\Constants\Constants;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class OrderReturnController extends Controller
{
    public function createReturnRequest(Request $request, $orderId)
    {
        
        $userId = $request->user()->id;

        $order = Order::where('_id', $orderId)
            ->where('user_id', $userId)
            ->firstOrFail();
        
        // Verify user owns the order and has been delivered
        if($order->status != Constants::STATUS_DELIVERED) {
            return response()->json(['errors' => ['order' => 'You can only return items that have been delivered.']], 403);
        }

        $orderItem = OrderItem::where('_id', $request->order_item_id)
            ->where('order_id', $orderId)
            ->firstOrFail();

        // Verify order has that item or not
        if (!$orderItem) {
            return response()->json(['errors' => ['order' =>  'Order item not found.']], 403);
        }

        // Explicit quantity validation
        if ($request->quantity > $orderItem->quantity) {
            return response()->json([
                'errors' => [
                    'quantity' => 'Return quantity cannot exceed originally ordered quantity of ' . $orderItem->quantity
                ]
            ], 422);
        }

        // Check if return window is still open
        if ($order->delivery_date && $orderItem->exchange_days) {
            $lastReturnDate = Carbon::parse($order->delivery_date)->addDays($orderItem->exchange_days);
            if (now()->gt($lastReturnDate)) {
                return response()->json([
                    'errors' => [
                        'order' => 'Return window closed. Last return date was ' . $lastReturnDate->format('M d, Y')
                    ]
                ], 403);
            }
        }

        // Check if this item already has a return request
        $existingReturn = ReturnRequest::where('order_item_id', $orderItem->id)->exists();

        if ($existingReturn) {
            return response()->json(['errors' => ['order' => 'This item is already in a return request.']], 400);
        }

        DB::beginTransaction();
        try {
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    $path = $image->store('returns/'.$order->id);
                    $imagePaths[] = $path;
                }
            }

            $amount = 0;
            $originalItemPrice = $orderItem->quantity * $orderItem->final_price;
            if ($order->promo_code_applied) {
                $orderDiscountPercentage = ($order->promo_code_disount / $order->order_amount) * 100;
                $discountedAmount = $originalItemPrice * ($orderDiscountPercentage / 100);
                $amount = $originalItemPrice - $discountedAmount;
            } else {
                $amount = $originalItemPrice;
            }

            $returnRequest = ReturnRequest::create([
                'order_id' => $orderId,
                'seller_id' => $order->seller_id,
                'order_item_id' => $orderItem->id,
                'product_id' => $orderItem->product_id,
                'user_id' => $userId,
                'reason' => $request->reason,
                'quantity' => $request->quantity,
                'status' => Constants::STATUS_PENDING,
                'refund_amount' => $amount,
                'refund_status' => Constants::STATUS_PENDING,
                'additional_notes' => $request->additional_notes,
                'requested_at' => now(),
                'images' => $imagePaths,
            ]);

            DB::commit();
            return response()->json([
                'message' => 'Return request submitted successfully',
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['errors' => ['server' => 'Failed to create order: ' . $e->getMessage()]], 500);
        }
    }

    public function getAllOrderReturnRequestList(Request $request) 
    {
        $user = $request->user();
        $limit = $request->input('limit', 10);
        $status = $request->input('status');
        $sellerId = $request->input('sellerId');
        $sellers =  Seller::where('status', Constants::STATUS_APPROVED )->get();
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        if (empty($sortBy)) {
            $sortBy = 'created_at';
        }

        $query = ReturnRequest::query();

        if (!$user->is_admin) {
            $query->where('seller_id', $user->sellerDetails->id);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if($sellerId) {
            $query->where('seller_id', $sellerId);
        }

        $sortOrder = in_array(strtolower($sortOrder), ['asc', 'desc']) ? strtolower($sortOrder) : 'asc';
        $query->orderBy($sortBy, $sortOrder);

        $requests = $query->paginate($limit);
        return view('order.return.list', compact('requests', 'limit', 'sellers'));
    }
}