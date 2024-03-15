<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index()
    {

        $user = auth()->user();
        $partner = $user->partner($user->email);
        $orders = [];
        $status = request()->get('status');
        $status = $this->getOrderStatus($status);
        $orderItems = OrderItem::join('products', 'order_items.product_id', '=', 'products.id')
            ->select('order_items.*', 'products.partner_id')
            ->get();

        foreach ($orderItems as $item)
          if($partner->id == $item->partner_id && $item->order->order_status == $status)
              $orders[] = $item;

        return view('shop.product-loans.index', ['orders'=> $orders,
                                                      'partner'=> $partner]);
    }
    private function getOrderStatus(string $status){
        switch ($status){
            case 'new':
                return 0;
            case 'pending-approval-loan':
                return 1;
            case 'pending-approval-financier':
                return 2;
            case 'pending-payment';
                return 3;
            case 'pending-dispatch':
                return 4;
            case 'dispatched-orders';
                return 5;
            default :
                return -1;
        }
    }

}
