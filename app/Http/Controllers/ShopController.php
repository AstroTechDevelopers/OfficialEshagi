<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        return view('shop.index');
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
            $products[] = Product::findOrFail(!is_int($productID) ? intval($productID) : $productID);

        foreach ($products as $product)
            $sum += $product->price;
        $products[] = ['total'=>$sum];
        return response($products, 201);
    }
    public function checkout()
    {
        return view('shop.checkout');
    }
    public function cart()
    {
        return view('shop.cart');
    }

}
