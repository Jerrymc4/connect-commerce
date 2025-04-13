<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    /**
     * Display the store dashboard.
     */
    public function index(): View
    {
        // Get the store preview URL
        $storePreviewUrl = route('storefront.home');
        
        // Date ranges for comparisons
        $today = Carbon::today();
        $startOfMonth = $today->copy()->startOfMonth();
        $endOfMonth = $today->copy()->endOfMonth();
        $startOfLastMonth = $today->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $today->copy()->subMonth()->endOfMonth();
        
        // Get store statistics
        $totalRevenue = $this->safeQuery(function() {
            return Order::sum('total');
        }, 0);
        
        $totalOrders = $this->safeQuery(function() {
            return Order::count();
        }, 0);
        
        $totalProducts = $this->safeQuery(function() {
            return Product::count();
        }, 0);
        
        $totalCustomers = $this->safeQuery(function() {
            return User::count();
        }, 0);
        
        // Get growth metrics (month over month)
        $currentMonthRevenue = $this->safeQuery(function() use ($startOfMonth, $endOfMonth) {
            return Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('total');
        }, 0);
        
        $lastMonthRevenue = $this->safeQuery(function() use ($startOfLastMonth, $endOfLastMonth) {
            return Order::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->sum('total');
        }, 0);
        
        $revenueGrowth = $lastMonthRevenue > 0 
            ? (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 
            : ($currentMonthRevenue > 0 ? 100 : 0);
            
        $currentMonthOrders = $this->safeQuery(function() use ($startOfMonth, $endOfMonth) {
            return Order::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
        }, 0);
        
        $lastMonthOrders = $this->safeQuery(function() use ($startOfLastMonth, $endOfLastMonth) {
            return Order::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();
        }, 0);
        
        $ordersGrowth = $lastMonthOrders > 0 
            ? (($currentMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100 
            : ($currentMonthOrders > 0 ? 100 : 0);
        
        // Low stock products count - Check if stock column exists
        $lowStockProducts = $this->safeQuery(function() {
            if (Schema::hasColumn('products', 'stock')) {
                return Product::where('stock', '<=', 5)
                    ->where('stock', '>', 0)
                    ->count();
            }
            return 0;
        }, 0);
            
        // New customers this week
        $newCustomers = $this->safeQuery(function() use ($today) {
            return User::where('created_at', '>=', $today->copy()->subWeek())->count();
        }, 0);
            
        // Recent orders
        $recentOrders = $this->safeQuery(function() {
            $query = Order::orderBy('created_at', 'desc')->take(5);
            
            // Check if user relationship exists before using it
            if (method_exists(Order::class, 'user')) {
                $query->with('user');
            }
            
            return $query->get()->map(function($order) {
                // Add status badge using the attribute from the model if it exists
                // otherwise calculate it here
                if (!isset($order->status_badge)) {
                    $order->status_badge = $this->getOrderStatusBadge($order->status ?? 'pending');
                }
                return $order;
            });
        }, collect([]));
        
        // Top products - check if orderItems relationship exists
        $topProducts = $this->safeQuery(function() {
            // Check if orderItems relationship exists
            if (method_exists(Product::class, 'orderItems')) {
                return Product::withCount(['orderItems as order_count' => function (Builder $query) {
                    $query->select(DB::raw('SUM(quantity)'));
                }])
                ->orderBy('order_count', 'desc')
                ->take(5)
                ->get();
            } else {
                // Fallback: just return some products
                return Product::latest()->take(5)->get();
            }
        }, collect([]));
        
        // Sales data for the chart (last 30 days)
        $salesData = $this->getSalesData(30);
        
        // Get store visitor statistics (dummy data for now)
        $totalVisitors = 1240; 
        $conversionRate = $totalVisitors > 0 ? ($totalOrders / $totalVisitors) * 100 : 0;
        
        // Notifications - simplified for demonstration
        $notifications = $this->getDemoNotifications();
        
        return view('store.dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'totalProducts',
            'totalCustomers',
            'revenueGrowth',
            'ordersGrowth',
            'lowStockProducts',
            'newCustomers',
            'recentOrders',
            'topProducts',
            'salesData',
            'totalVisitors',
            'conversionRate',
            'notifications',
            'storePreviewUrl'
        ));
    }
    
    /**
     * Safely execute a database query and return a default value if it fails.
     */
    private function safeQuery(callable $callback, $default = null)
    {
        try {
            return $callback();
        } catch (\Exception $e) {
            // Log the error in a production environment
            // \Log::error('Dashboard query error: ' . $e->getMessage());
            return $default;
        }
    }
    
    /**
     * Get demo notifications for the dashboard.
     */
    private function getDemoNotifications(): Collection
    {
        $notifications = collect();
        
        // Add some sample notifications for demo purposes
        $notifications->push([
            'id' => 'demo_1',
            'type' => 'alert',
            'title' => 'Welcome to Your Dashboard',
            'message' => 'This is your store dashboard where you can manage all aspects of your business.',
            'created_at' => Carbon::now()->subHours(2)
        ]);
        
        $notifications->push([
            'id' => 'demo_2',
            'type' => 'order',
            'title' => 'New Order Example',
            'message' => 'When you receive orders, they will appear here.',
            'created_at' => Carbon::now()->subHours(5)
        ]);
        
        $notifications->push([
            'id' => 'demo_3',
            'type' => 'customer',
            'title' => 'New Customer Example',
            'message' => 'Customer notifications will appear here when people create accounts.',
            'created_at' => Carbon::now()->subHours(8)
        ]);
        
        // Convert to objects and sort by created_at
        return $notifications->map(function($item) {
            return (object) [
                'id' => $item['id'],
                'type' => $item['type'],
                'title' => $item['title'],
                'message' => $item['message'],
                'created_at' => $item['created_at'] instanceof Carbon 
                    ? $item['created_at'] 
                    : Carbon::parse($item['created_at'])
            ];
        })->sortByDesc('created_at');
    }
    
    /**
     * Get the sales data for the chart.
     */
    private function getSalesData(int $days): Collection
    {
        $startDate = Carbon::now()->subDays($days);
        $endDate = Carbon::now();
        
        return $this->safeQuery(function() use ($startDate, $endDate, $days) {
            // Check if Orders table exists and has required columns
            if (!Schema::hasTable('orders') || !Schema::hasColumn('orders', 'total') || !Schema::hasColumn('orders', 'created_at')) {
                return $this->getDemoSalesData($days);
            }
            
            // Get daily sales for the period
            $salesData = Order::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(total) as total')
                )
                ->where('created_at', '>=', $startDate)
                ->where('created_at', '<=', $endDate)
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('date')
                ->get();
                
            // Fill in missing dates with zero values
            $completeData = collect();
            $currentDate = $startDate->copy();
            
            while ($currentDate <= $endDate) {
                $dateString = $currentDate->format('Y-m-d');
                $dayData = $salesData->firstWhere('date', $dateString);
                
                $completeData->push([
                    'date' => $currentDate->format('M d'),
                    'total' => $dayData ? (float)$dayData->total : 0
                ]);
                
                $currentDate->addDay();
            }
            
            return $completeData;
        }, $this->getDemoSalesData($days));
    }
    
    /**
     * Get demo sales data for the chart when real data is not available.
     */
    private function getDemoSalesData(int $days): Collection
    {
        $data = collect();
        $startDate = Carbon::now()->subDays($days);
        
        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy()->addDays($i);
            $data->push([
                'date' => $date->format('M d'),
                'total' => rand(50, 500) // Random sales between $50 and $500
            ]);
        }
        
        return $data;
    }
    
    /**
     * Get the CSS class for order status badges.
     */
    private function getOrderStatusBadge(string $status): string
    {
        return match ($status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'processing' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            'refunded' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
} 