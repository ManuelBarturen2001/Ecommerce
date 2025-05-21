<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\NewsletterSubscriber;
use App\Models\Order;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard(Request $request)
    {
        // Fechas por defecto si no se proporcionan
        $startDate = $request->input('start_date') ? Carbon::parse($request->input('start_date')) : Carbon::now()->startOfMonth();
        $endDate = $request->input('end_date') ? Carbon::parse($request->input('end_date')) : Carbon::now();
        
        // Datos generales
        $todaysOrder = Order::whereDate('created_at', Carbon::today())->count();
        $todaysPendingOrder = Order::whereDate('created_at', Carbon::today())
            ->where('order_status', 'pending')->count();
        $totalOrders = Order::count();
        $totalPendingOrders = Order::where('order_status', 'pending')->count();
        $totalCanceledOrders = Order::where('order_status', 'canceled')->count();
        $totalCompleteOrders = Order::where('order_status', 'delivered')->count();

        $todaysEarnings = Order::where('order_status','!=', 'canceled')
            ->where('payment_status', 1)
            ->whereDate('created_at', Carbon::today())
            ->sum('sub_total');

        $monthEarnings = Order::where('order_status','!=', 'canceled')
            ->where('payment_status', 1)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('sub_total');

        $yearEarnings = Order::where('order_status','!=', 'canceled')
            ->where('payment_status', 1)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('sub_total');

        // Datos para gráficos según el rango de fechas
        $chartType = $request->input('chart_type', 'monthly');
        
        // Datos para el gráfico de ingresos
        switch ($chartType) {
            case 'daily':
                $revenueData = $this->getDailyRevenue($startDate, $endDate);
                break;
            case 'yearly':
                $revenueData = $this->getYearlyRevenue($startDate, $endDate);
                break;
            case 'monthly':
            default:
                $revenueData = $this->getMonthlyRevenue($startDate, $endDate);
                break;
        }
        
        // Datos para el gráfico de estado de pedidos
        $orderStatusData = $this->getOrderStatusData($startDate, $endDate);
        
        // Otros contadores
        $totalReview = ProductReview::count();
        $totalBrands = Brand::count();
        $totalCategories = Category::count();
        $totalBlogs = Blog::count();
        $totalSubscriber = NewsletterSubscriber::count();
        $totalVendors = User::where('role', 'vendor')->count();
        $totalUsers = User::where('role', 'user')->count();

        return view('admin.dashboard', compact(
            'todaysOrder',
            'todaysPendingOrder',
            'totalOrders',
            'totalPendingOrders',
            'totalCanceledOrders',
            'totalCompleteOrders',
            'todaysEarnings',
            'monthEarnings',
            'yearEarnings',
            'totalReview',
            'totalBrands',
            'totalCategories',
            'totalBlogs',
            'totalSubscriber',
            'totalVendors',
            'totalUsers',
            'revenueData',
            'orderStatusData',
            'startDate',
            'endDate',
            'chartType'
        ));
    }
    
    /**
     * Obtener ingresos diarios
     */
    private function getDailyRevenue($startDate, $endDate)
    {
        $dailyRevenue = Order::where('order_status', '!=', 'canceled')
            ->where('payment_status', 1)
            ->whereBetween('created_at', [$startDate, $endDate->endOfDay()])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(sub_total) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
            
        // Formatear datos para el gráfico
        $labels = [];
        $data = [];
        
        // Crear array de fechas completo (incluso días sin ventas)
        $period = new \DatePeriod(
            $startDate,
            new \DateInterval('P1D'),
            $endDate->addDay() // Añadir un día para incluir la fecha final
        );
        
        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');
            $labels[] = $date->format('d/m');
            
            // Buscar si hay ingresos para esta fecha
            $revenue = $dailyRevenue->firstWhere('date', $dateStr);
            $data[] = $revenue ? round($revenue->total, 2) : 0;
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
    
    /**
     * Obtener ingresos mensuales
     */
    private function getMonthlyRevenue($startDate, $endDate)
    {
        $monthlyRevenue = Order::where('order_status', '!=', 'canceled')
            ->where('payment_status', 1)
            ->whereBetween('created_at', [$startDate->startOfMonth(), $endDate->endOfMonth()])
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(sub_total) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        // Formatear datos para el gráfico
        $labels = [];
        $data = [];
        
        // Crear array de meses completo
        $period = new \DatePeriod(
            $startDate->copy()->startOfMonth(),
            new \DateInterval('P1M'),
            $endDate->copy()->endOfMonth()->addDay() // Añadir un día para incluir el mes final
        );
        
        foreach ($period as $date) {
            $year = $date->format('Y');
            $month = $date->format('n');
            $labels[] = $date->format('M Y');
            
            // Buscar si hay ingresos para este mes
            $revenue = $monthlyRevenue->first(function($item) use ($year, $month) {
                return $item->year == $year && $item->month == $month;
            });
            
            $data[] = $revenue ? round($revenue->total, 2) : 0;
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
    
    /**
     * Obtener ingresos anuales
     */
    private function getYearlyRevenue($startDate, $endDate)
    {
        $yearlyRevenue = Order::where('order_status', '!=', 'canceled')
            ->where('payment_status', 1)
            ->whereBetween('created_at', [$startDate->startOfYear(), $endDate->endOfYear()])
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(sub_total) as total')
            )
            ->groupBy('year')
            ->orderBy('year')
            ->get();
        
        // Formatear datos para el gráfico
        $labels = [];
        $data = [];
        
        // Crear array de años completo
        $startYear = $startDate->year;
        $endYear = $endDate->year;
        
        for ($year = $startYear; $year <= $endYear; $year++) {
            $labels[] = $year;
            
            // Buscar si hay ingresos para este año
            $revenue = $yearlyRevenue->firstWhere('year', $year);
            $data[] = $revenue ? round($revenue->total, 2) : 0;
        }
        
        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
    
    /**
     * Obtener datos de estado de pedidos
     */
    private function getOrderStatusData($startDate, $endDate)
    {
        $completed = Order::where('order_status', 'delivered')
            ->whereBetween('created_at', [$startDate, $endDate->endOfDay()])
            ->count();
            
        $pending = Order::where('order_status', 'pending')
            ->whereBetween('created_at', [$startDate, $endDate->endOfDay()])
            ->count();
            
        $canceled = Order::where('order_status', 'canceled')
            ->whereBetween('created_at', [$startDate, $endDate->endOfDay()])
            ->count();
            
        return [
            'labels' => ['Completados', 'Pendientes', 'Cancelados'],
            'data' => [$completed, $pending, $canceled]
        ];
    }

    public function login()
    {
        return view('admin.auth.login');
    }
}