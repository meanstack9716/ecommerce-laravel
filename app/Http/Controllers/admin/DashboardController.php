<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Seller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Constants\Constants;

class DashboardController extends Controller
{
    public function viewDashboard(Request $request) 
    {
        $user = $request->user();
        $sellers = Seller::where('status', Constants::STATUS_APPROVED)->get();
        return view('dashboard.index', compact('sellers'));
    }

    private function getDateRange(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        
        // Default to last 30 days if no dates provided
        if (!$startDate || !$endDate) {
            $endDate = Carbon::now();
            $startDate = Carbon::now()->subDays(30);
            return [$startDate, $endDate];
        }
        
        return [
            Carbon::parse($startDate)->startOfDay(),
            Carbon::parse($endDate)->endOfDay()
        ];
    }

    private function getComparisonDateRange($startDate, $endDate)
    {
        $diffInDays = $startDate->diffInDays($endDate);
        
        return [
            $startDate->copy()->subDays($diffInDays + 1),
            $startDate->copy()->subSecond()
        ];
    }

    private function filterSeller($user, $sellerId)
    {
        $query = Order::query();

        if ($user->is_admin) {
            if ($sellerId) {
                $seller = Seller::find($sellerId);
                if (!$seller) {
                    throw new \Exception('Seller profile not found');
                }
                $query->where('seller_id', $seller->id);
            }
        } else {
            $seller = Seller::where('user_id', $user->id)->first();
            if (!$seller) {
                throw new \Exception('Seller profile not found');
            }
            $query->where('seller_id', $seller->id);
        }

        return $query;
    }

    public function recentOrders(Request $request)
    {
        try {
            $user = $request->user();
            $sellerId = $request->query('seller_id');
            $limit = (int) $request->query('limit', 5);
            $page = (int) $request->query('page', 1);
            
            [$startDate, $endDate] = $this->getDateRange($request);

            $query = $this->filterSeller($user, $sellerId)
                ->whereBetween('created_at', [$startDate, $endDate]);

            $totalOrders = $query->count();
            $orders = $query->with(['user', 'items.product'])
                ->orderByDesc('created_at')
                ->skip(($page - 1) * $limit)
                ->take($limit)
                ->get();
            
            $recentOrders = $orders->map(function ($order) {
                $customerName = 'Unknown';
                if ($order->user) {
                    $customerName = trim("{$order->user->first_name} {$order->user->last_name}");
                    if (!$customerName) {
                        $customerName = explode('@', $order->user->email)[0];
                    }
                }

                return [
                    'order_id' => $order->order_number,
                    'customer' => $customerName,
                    'payment_method' => $order->payment_method,
                    'payment_status' => $order->payment_status,
                    'amount' => floatval($order->total_amount),
                    'date' => $order->created_at->format('Y-m-d'),
                    'status' => $order->status
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => [
                    'orders' => $recentOrders,
                    'total' => $totalOrders,
                    'page' => $page,
                    'per_page' => $limit
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function salesOverview(Request $request)
    {
        try {
            $user = $request->user();
            $sellerId = $request->query('seller_id');
            
            [$startDate, $endDate] = $this->getDateRange($request);
            [$prevStartDate, $prevEndDate] = $this->getComparisonDateRange($startDate, $endDate);

            $query = $this->filterSeller($user, $sellerId)
                ->whereBetween('created_at', [$startDate, $endDate]);
                
            $prevQuery = $this->filterSeller($user, $sellerId)
                ->whereBetween('created_at', [$prevStartDate, $prevEndDate]);

            $totalSales = $query->sum('total_amount') ?: 0;
            $prevTotalSales = $prevQuery->sum('total_amount') ?: 0;
            $salesChange = $prevTotalSales > 0 ? (($totalSales - $prevTotalSales) / $prevTotalSales * 100) : ($totalSales > 0 ? 100 : 0);

            $orderCount = $query->count() ?: 0;
            $prevOrderCount = $prevQuery->count() ?: 0;
            $orderChange = $prevOrderCount > 0 ? (($orderCount - $prevOrderCount) / $prevOrderCount * 100) : ($orderCount > 0 ? 100 : 0);

            $avgOrderValue = $orderCount > 0 ? ($totalSales / $orderCount) : 0;
            $prevAvgOrderValue = $prevOrderCount > 0 ? ($prevTotalSales / $prevOrderCount) : 0;
            $avgOrderChange = $prevAvgOrderValue > 0 ? (($avgOrderValue - $prevAvgOrderValue) / $prevAvgOrderValue * 100) : ($avgOrderValue > 0 ? 100 : 0);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'total_sales' => floatval($totalSales),
                    'total_sales_change' => floatval($salesChange),
                    'order_count' => $orderCount,
                    'order_count_change' => floatval($orderChange),
                    'avg_order_value' => floatval($avgOrderValue),
                    'avg_order_change' => floatval($avgOrderChange),
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function salesOverTime(Request $request)
    {
        try {
            $user = $request->user();
            $sellerId = $request->query('seller_id');
            $period = $request->query('period', 'daily');
            
            [$startDate, $endDate] = $this->getDateRange($request);
            
            $labels = [];
            $salesData = [];
            
            if ($period === 'monthly') {
                $current = $startDate->copy()->startOfMonth();
                while ($current <= $endDate) {
                    $monthEnd = $current->copy()->endOfMonth();
                    if ($monthEnd > $endDate) {
                        $monthEnd = $endDate;
                    }
                    
                    $labels[] = $current->format('M Y');
                    $salesData[] = $this->filterSeller($user, $sellerId)
                        ->whereBetween('created_at', [$current, $monthEnd])
                        ->sum('total_amount') ?: 0;
                        
                    $current = $monthEnd->copy()->addDay()->startOfMonth();
                }
            } elseif ($period === 'weekly') {
                $current = $startDate->copy()->startOfWeek();
                while ($current <= $endDate) {
                    $weekEnd = $current->copy()->endOfWeek();
                    if ($weekEnd > $endDate) {
                        $weekEnd = $endDate;
                    }
                    
                    $labels[] = 'Week ' . $current->weekOfYear;
                    $salesData[] = $this->filterSeller($user, $sellerId)
                        ->whereBetween('created_at', [$current, $weekEnd])
                        ->sum('total_amount') ?: 0;
                        
                    $current = $weekEnd->copy()->addDay()->startOfWeek();
                }
            } else { // daily
                $current = $startDate->copy();
                while ($current <= $endDate) {
                    $dayEnd = $current->copy()->endOfDay();
                    
                    $labels[] = $current->format('d M');
                    $salesData[] = $this->filterSeller($user, $sellerId)
                        ->whereBetween('created_at', [$current, $dayEnd])
                        ->sum('total_amount') ?: 0;
                        
                    $current = $dayEnd->copy()->addSecond();
                }
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'labels' => $labels,
                    'sales' => $salesData,
                    'period' => $period
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function topProducts(Request $request)
    {
        try {
            $user = $request->user();
            $sellerId = $request->query('seller_id');
            
            [$startDate, $endDate] = $this->getDateRange($request);

            $query = $this->filterSeller($user, $sellerId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->with('items.product');

            $orders = $query->get();
            $productSales = [];
            $totalSales = $query->sum('total_amount') ?: 0;

            foreach ($orders as $order) {
                foreach ($order->items as $item) {
                    if ($item->product) {
                        $productId = (string) $item->product_id;
                        $amount = floatval($item->quantity * $item->price);
                        $productSales[$productId] = array_merge(
                            $productSales[$productId] ?? ['name' => $item->product->title, 'amount' => 0],
                            ['amount' => ($productSales[$productId]['amount'] ?? 0) + $amount]
                        );
                    }
                }
            }

            $topProducts = collect($productSales)
                ->map(function ($data, $productId) use ($totalSales) {
                    return [
                        'name' => $data['name'],
                        'amount' => $data['amount'],
                        'percentage' => $totalSales > 0 ? ($data['amount'] / $totalSales * 100) : 0
                    ];
                })
                ->sortByDesc('amount')
                ->take(4)
                ->values()
                ->all();

            return response()->json([
                'status' => 'success',
                'data' => $topProducts
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}