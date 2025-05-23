<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Seller;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Constants\Constants;

class DashboardController extends Controller
{
    public function viewDashboard(Request $request) 
    {
        $user = $request->user();
        $sellers =  Seller::where('status', Constants::STATUS_APPROVED )->get();
        return view('dashboard.index', compact('sellers'));
    }


    public function getPeriodDates(string $period, $now = null)
    {
        $now = $now instanceof Carbon ? $now : Carbon::now();
        $startDate = null;
        $endDate = null;

        switch ($period) {
            case 'today':
                $startDate = $now->copy()->startOfDay();
                $endDate = $now->copy()->endOfDay();
                break;

            case 'yesterday':
                $startDate = $now->copy()->subDay()->startOfDay();
                $endDate = $now->copy()->subDay()->endOfDay();
                break;

            case 'this_week':
                $startDate = $now->copy()->startOfWeek();
                $endDate = $now->copy()->endOfWeek();
                break;

            case 'last_week':
                $startDate = $now->copy()->subWeek()->startOfWeek();
                $endDate = $now->copy()->subWeek()->endOfWeek();
                break;
            
            case 'this_month':
                $startDate = $now->copy()->startOfMonth();
                $endDate = $now->copy()->endOfMonth();
                break;

            case 'last_month':
                $startDate = $now->copy()->subMonth()->startOfMonth();
                $endDate = $now->copy()->subMonth()->endOfMonth();
                break;

            case 'this_quarter':
                $startDate = $now->copy()->startOfQuarter();
                $endDate = $now->copy()->endOfQuarter();
                break;

            case 'last_quarter':
                $startDate = $now->copy()->subQuarter()->startOfQuarter();
                $endDate = $now->copy()->subQuarter()->endOfQuarter();
                break;

            case 'this_year':
                $startDate = $now->copy()->startOfYear();
                $endDate = $now->copy()->endOfYear();
                break;
            
            case 'last_year':
                $startDate = $now->copy()->subYear()->startOfYear();
                $endDate = $now->copy()->subYear()->endOfYear();
                break;

            default:
                throw new \InvalidArgumentException("Unknown period: {$period}");
        }

        return [$startDate, $endDate];
    }

    private function filterSellerAndPeriod($user, $sellerId, $startDate, $endDate)
    {
        $query = Order::whereBetween('created_at', [$startDate, $endDate]);

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
            $period = $request->query('period', 'this_month');
            [$startDate, $endDate] = $this->getPeriodDates($period);

            $query = $this->filterSellerAndPeriod($user, $sellerId, $startDate, $endDate);
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
            $period = $request->query('period', 'this_month');
            
            [$startDate, $endDate] = $this->getPeriodDates($period);
            $prevEndDate = $startDate->copy()->subSecond();
            $prevStartDate = $prevEndDate->copy();
            
            if ($period === 'this_month') {
                $prevStartDate = $startDate->copy()->subMonth()->startOfMonth();
            } elseif ($period === 'last_month') {
                $prevStartDate = $startDate->copy()->subMonth()->startOfMonth();
                $prevEndDate = $startDate->copy()->subSecond();
            } elseif ($period === 'this_quarter') {
                $prevStartDate = $startDate->copy()->subQuarter()->startOfQuarter();
            } elseif ($period === 'this_year') {
                $prevStartDate = $startDate->copy()->subYear()->startOfYear();
                $prevEndDate = $prevStartDate->copy()->endOfYear();
            }

            $query = $this->filterSellerAndPeriod($user, $sellerId, $startDate, $endDate);
            $prevQuery = $this->filterSellerAndPeriod($user, $sellerId, $prevStartDate, $prevEndDate);

            $orders = $query->get();
            $prevOrders = $prevQuery->get();

            $totalSales = $orders->sum('total_amount') ?: 0;
            $prevTotalSales = $prevOrders->sum('total_amount') ?: 0;
            $salesChange = $prevTotalSales > 0 ? (($totalSales - $prevTotalSales) / $prevTotalSales * 100) : ($totalSales > 0 ? 100 : 0);

            $orderCount = $orders->count() ?: 0;
            $prevOrderCount = $prevOrders->count() ?: 0;
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
            $period = $request->query('period', 'monthly');
            $filterPeriod = $request->query('filter_period', 'this_month');

            [$startDate, $endDate] = $this->getPeriodDates($filterPeriod);
            $labels = [];
            $salesData = [];

            if ($period === 'monthly') {
                $months = $startDate->diffInMonths($endDate) + 1;
                for ($i = 0; $i < $months; $i++) {
                    $monthStart = $startDate->copy()->addMonths($i)->startOfMonth();
                    $monthEnd = $monthStart->copy()->endOfMonth();
                    if ($monthEnd <= $endDate) {
                        $labels[] = $monthStart->format('M Y');
                        $salesData[] = $this->getSalesForPeriod($user, $sellerId, $monthStart, $monthEnd);
                    }
                }
            } elseif ($period === 'weekly') {
                $weeks = $startDate->diffInWeeks($endDate) + 1;
                for ($i = 0; $i < $weeks; $i++) {
                    $weekStart = $startDate->copy()->addWeeks($i)->startOfWeek();
                    $weekEnd = $weekStart->copy()->endOfWeek();
                    if ($weekEnd <= $endDate) {
                        $labels[] = "Week {$weekStart->weekOfYear}";
                        $salesData[] = $this->getSalesForPeriod($user, $sellerId, $weekStart, $weekEnd);
                    }
                }
            } else { // daily
                $days = $startDate->diffInDays($endDate) + 1;
                for ($i = 0; $i < $days; $i++) {
                    $dayStart = $startDate->copy()->addDays($i)->startOfDay();
                    $dayEnd = $dayStart->copy()->endOfDay();
                    if ($dayEnd <= $endDate) {
                        $labels[] = $dayStart->format('d M');
                        $salesData[] = $this->getSalesForPeriod($user, $sellerId, $dayStart, $dayEnd);
                    }
                }
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'labels' => array_reverse($labels),
                    'sales' => array_reverse($salesData),
                    'period' => $period
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    private function getSalesForPeriod($user, $sellerId, $startDate, $endDate)
    {
        $query = $this->filterSellerAndPeriod($user, $sellerId, $startDate, $endDate);
        return floatval($query->sum('total_amount') ?: 0);
    }

    public function topProducts(Request $request)
    {
        try {
            $user = $request->user();
            $sellerId = $request->query('seller_id');
            $period = $request->query('period', 'this_month');

            [$startDate, $endDate] = $this->getPeriodDates($period);

            $query = $this->filterSellerAndPeriod($user, $sellerId, $startDate, $endDate);

            $orders = $query->with('items.product')->get();
            $productSales = [];
            // $totalSales = 0;
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
                        // $totalSales += $amount;
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