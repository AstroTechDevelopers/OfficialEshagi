<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        return view('shop.shop');
    }
    public function productShow(int $id)
    {
        $product = Product::findOrFail($id);

        return view('shop.product-details', ['product'=>$product]);
    }
    public function getProducts(Request  $request)
    {
        $products = array();
        $sum = 0;
        $productIDs = explode(',',$request->input('products'));

        foreach ($productIDs as $productID)
            if($productID != "")
                $products[] =  Product::findOrFail(!is_int($productID) ? intval($productID) : $productID);

        foreach ($products as $product)
            $sum += $product->price;
        $products[] = ['total'=>$sum];
        return response($products, 201);
    }
    private function cleanArray(Array $productIds){
        $array = [];
        foreach ($productIds as $productId){
            if($productId != ""){
                $array[] = $productId;
            }
        }

        return $array;
    }
    public function postCheckout(Request $request)
    {

        $keys = array_keys($request->all());
        $filteredKeys = [];

        foreach ($keys as $key)
            if($key != '_token' && is_int($key))
               $filteredKeys[] = $key;

        $order = new Order();
        $order->order_status = 'NEW';
        $order->payment_method = 'cash';
        $order->user_id = auth()->user()->id;
        $order->save();
        $orderItems = [];
        foreach ($filteredKeys as $k) {
            if($order){
                $orderItem = [
                  'product_id'=>$k,
                  'quantity' =>$request->$k,
                  'order_id' =>$order->id
                ];
                $orderItems[] = $orderItem;
            }
        }

        OrderItem::insert($orderItems);

        $products = array();
        $productIDs = array();
        foreach ($orderItems as $orderItem)
            $productIDs[] =  $orderItem['product_id'];

        return redirect(url('checkout/'.$order->id));
    }

    public function placeOrder(Request $request)
    {
        $productIDs = explode(',',$request->input('products'));
        $productIDs = $this->cleanArray($productIDs);

        $orderArray = [

        ];
        $orderItems = array ();
        foreach( $productIDs as $productID)
        {
            $orderItems[] = [
                'product_id' =>$productID,
                'quantity' =>1
            ];
        }

    }
    public function checkout(int $id)
    {
        $productIDs = array();
        $orderItems = OrderItem::where('order_id', $id)->get();
        $subTotal = 0;
        foreach($orderItems as $orderItem) {
            $productIDs[] = $orderItem->product_id;
            $subTotal += ($orderItem->product->price * $orderItem->quantity);
        }
        foreach ($productIDs as $prod)
            $products[] = Product::findOrFail($prod);

        $productsByPartner = Product::whereIn('id', $productIDs)
            ->groupBy('partner_id')
            ->selectRaw('partner_id, SUM(price) as total_amount')
            ->get();


        $commision = 0.03 * $subTotal;

        return view('shop.checkout', ['products'=>$products,
                                            'orders'=>$orderItems,
                                            'creditors'=>$productsByPartner,
                                            'subTotal'=>$subTotal,
                                            'orderId' => $id,
                                            'commission'=>$commision]
        );
    }
    public function cart()
    {
        return view('shop.cart');
    }

    public function openCart(Request  $request)
    {
        $products = array();
        $productIDs = explode(',',$request->input('products'));
        $productIDs = $this->cleanArray($productIDs);

        foreach($productIDs as $productID)
            $products [] = Product::findOrFail($productID);

        return view('shop.cart', ['products'=>$products]);
    }

    public function updateCart(Request $request)
    {
        dd($request->all());
    }

    public function shopProducts($partnerId)
    {
        $products = Product::where('partner_id', $partnerId)->get();

        return view('shop.shop_products', ['products'=>$products]);
    }

}
