<?php

namespace App\Http\Controllers\Frontend;

use App\DataTables\UserOrderDataTable;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserOrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', Auth::user()->id)
                       ->orderBy('created_at', 'desc')
                       ->get();
        
        return view('frontend.dashboard.order.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::where('user_id', Auth::user()->id)
                     ->where('id', $id)
                     ->firstOrFail();
        
        return view('frontend.dashboard.order.show', compact('order'));
    }
}
