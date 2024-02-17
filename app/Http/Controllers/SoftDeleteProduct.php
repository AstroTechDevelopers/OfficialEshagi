<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SoftDeleteProduct extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Get Soft Deleted Product.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public static function getDeletedProduct($id)
    {
        $product = Product::onlyTrashed()->where('id', $id)->get();
        if (count($product) !== 1) {
            return redirect('/products/deleted/')->with('error', 'No deleted products found so far');
        }

        return $product[0];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::onlyTrashed()->get();

        return view('products.deleted-products', compact('products'));
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = self::getDeletedProduct($id);
        return view('products.deleted-product', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = self::getDeletedProduct($id);
        $product->restore();

        return redirect('/products/')->with('success', 'Product restored successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = self::getDeletedProduct($id);
        $product->forceDelete();

        return redirect('/products/deleted/')->with('success', 'Product completely deleted.');
    }
}
