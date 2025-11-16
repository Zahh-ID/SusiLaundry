<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class OrderPrintController extends Controller
{
    public function __invoke(Order $order)
    {
        $order->load(['customer', 'package']);

        return view('admin.orders.print', compact('order'));
    }
}
