<?php

namespace App\Http\Controllers;

use App\Imports\ProductsImport;
use App\Models\Partner;
use App\Models\Prodexchange;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
//use Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();
        return view('products.products', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all()->pluck('category_name', 'id')->toArray();
        return view('products.add-product', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'creator'        => 'required',
                'category_id' => 'required',
                'pcode'       => 'required|unique:products',
                'pcode'       => 'required|unique:products',
                'serial'       => 'required|unique:products',
                'pname'    => 'required',
                'model'     => 'required',
                'descrip'     => 'required',
                'price'     => 'required',
                'product_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            ],
            [
                'creator.required'          => 'Make sure you\'re logged in.',
                'category_id.required'        => 'What is the product category for the product?',
                'pcode.required'        => 'What is the product code for the product?',
                'pcode.unique'        => 'Product code is already in the system.',
                'serial.required'       => 'What is the serial number of the product?',
                'serial.unique'       => 'We already have this serial number in the system?',
                'pname.required'    => 'What is the product name?',
                'model.required'     => 'The product model name is required.',
                'descrip.required'     => 'What is the product description?',
                'price.required'     => 'What is the product price?',
                'product_image.max' => 'Product Image should not be greater than 4MB.',
                'product_image.mimes' => 'Product Image should of the format: jpeg,png,jpg,gif,svg.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        /*$partner = DB::table('partners')
            ->where('regNumber',auth()->user()->natid)
            ->first();*/

        $filename = '';

        $partner = DB::table('partners')
            ->where('regNumber',auth()->user()->natid)
            ->first();

        if($request->hasFile('product_image')) {
            if ($request->file('product_image')->isValid()) {
               $productImage = $request->file('product_image');
               $filename = 'pi_' . substr($request->input('pcode'),2) . "_" . $partner->id . '.' . $productImage->getClientOriginalExtension();
               Storage::disk('public')->put('merchants/products/' . $filename, File::get($productImage));
            } else {
               return back()->withErrors(['product_image'=>'Invalid image supplied.'])->withInput();
            }
        } else {
           return back()->withErrors(['product_image'=>'No file was detected here.'])->withInput();
        }

              if ($request->input('loandevice') =='on'){
                 $isLoanDevice = true;
              } else {
                 $isLoanDevice = false;
              }

            $product = Product::create([
                'loandevice'             => $isLoanDevice,
                'creator'             => $request->input('creator'),
                'pcode'             => $request->input('pcode'),
                'serial'             => $request->input('serial'),
                'pname'             => $request->input('pname'),
                'model'             => $request->input('model'),
                'descrip'             => $request->input('descrip'),
                'price'             => $request->input('price'),
                'partner_id'        => $partner->id,
                'product_category_id' => $request->input('category_id'),
                'product_image' => $filename,
            ]);

            $product->save();

            return redirect()->back()->with('success', 'Product added successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return view('products.show-product', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit-product', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $filename='';
        $validator = Validator::make($request->all(),
            [
                'category_id' => 'required',
                'pcode'       => 'required|unique:products,pcode,'.$product->id,
                'serial'       => 'required|unique:products,serial,'.$product->id,
                'pname'    => 'required',
                'model'     => 'required',
                'descrip'     => 'required',
                'price'     => 'required',
                'product_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
            ],
            [
                'category_id.required'        => 'What is the product category for the product?',
                'pcode.required'        => 'What is the product code for the product?',
                'pcode.unique'        => 'Product code is already in the system.',
                'serial.required'       => 'What is the serial number of the product?',
                'serial.unique'       => 'We already have this serial number in the system?',
                'pname.required'    => 'What is the product name?',
                'model.required'     => 'The product model name is required.',
                'descrip.required'     => 'What is the product description?',
                'price.required'     => 'What is the product price?',
                'product_image.max' => 'Product Image should not be greater than 4MB.',
                'product_image.mimes' => 'Product Image should of the format: jpeg,png,jpg,gif,svg.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($request->input('loandevice') =='on'){
            $isLoanDevice = true;
        } else {
            $isLoanDevice = false;
        }

        $partner = DB::table('partners')
            ->where('regNumber',auth()->user()->natid)
            ->first();

        if($request->hasFile('product_image')) {
            if ($request->file('product_image')->isValid()) {
               $productImage = $request->file('product_image');
               $filename = 'pi_' . substr($request->input('pcode'),2) . "_" . $partner->id . '.' . $productImage->getClientOriginalExtension();
               Storage::disk('public')->put('merchants/products/' . $filename, File::get($productImage));
            } else {
               return back()->withErrors(['product_image'=>'Invalid image supplied.'])->withInput();
            }
        } else {
           return back()->withErrors(['product_image'=>'No file was detected here.'])->withInput();
        }

        $product->loandevice = $isLoanDevice;
        $product->pcode = $request->input('pcode');
        $product->serial = $request->input('serial');
        $product->pname = $request->input('pname');
        $product->model = $request->input('model');
        $product->descrip = $request->input('descrip');
        $product->price = $request->input('price');
        //$product->usd_price = $request->input('usd_price');
        $product->product_category_id = $request->input('category_id');
        $product->product_image = $filename;

        $product->save();

        return redirect()->back()->with('success', 'Product info updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect('products')->with('success', 'Product deleted successfully.');
    }

    public function getOurProducts() {
        $products = Product::where('creator', auth()->user()->name)->get();
        return view('products.partner-products', compact('products'));
    }

    public function showImportProductsForm() {
        if (auth()->user()->utype != 'Partner'){
            $partners = User::where('utype','=','Partner')->get();

            return view('products.bulk-import-products', compact('partners'));

        }

        return view('products.bulk-import-products');
    }

    public function importBulkProducts(Request $request){

        $validator = Validator::make(
            $request->all(),
            [
                'products_excel'  => 'required|mimes:csv,xlsx',
                'creator'  => 'required',
            ],
            [
                'creator.required'  => 'Who is the owner of the products?.',
                'products_excel.required'  => 'No import file was found here.',
                'products_excel.mimes'     => 'Import file should of the format: csv,xlsx.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('products_excel')) {
            $file = $request->file('products_excel');
            $data = Excel::toArray([], $file);

            $products = array();
            foreach($data[0] as $product){
                $productObj = [
                    'loandevice'=>0,
                    'creator'=>auth()->user()->name,
                    'partner_id'=>Partner::where('regNumber', auth()->user()->natid)->first()->id,
                    'product_category_id'=>1,
                    'pcode'=>'0',
                    'serial'=>'',
                    'pname' =>'',
                    'model'=>'',
                    'descrip'=>'',
                    'price'=>''
                ];
                for ($i = 0 ; $i < count($product) ; $i++){
                   switch ($i){
                       case 0 :
                           $productObj['pcode'] = $product[$i];
                       case 1 :
                           $productObj['serial']= $product[$i];
                       case 2 :
                           $productObj['pname'] = $product[$i];
                       case 3 :
                           $productObj['model'] = $product[$i];
                       case 4 :
                           $productObj['descrip']=$product[$i];
                       case 5 :
                           $productObj['price']  =$product[$i];
                   }

                }
                $products[] = $productObj;

               /* foreach ($product as $row){
                    $product = [
                        'loandevice'=> 0,
                        'creator' => auth()->user()->name,
                        'pcode' => $row[0],
                        'serial' => $row[1],
                        'pname' => $row[2],
                        'model' => $row[3],
                        'partner_id' =>Partner::where('regNumber', auth()->user()->natid)->first()->id ,
                        'product_category_id' => 1,
                        'descrip' => $row[4],
                        'price' => $row[5]
                    ];
                    $products[] = $product;
                }
                dd('here'); */
            }

        }

        Product::insert($products);
        //Excel::import(new ProductsImport, request()->file('products_excel'));

        return redirect()->back()->with('success', 'Products Import completed successfully.');
    }

    public function pricingTemplate() {
        $rate = Prodexchange::where('user_id', '=',auth()->user()->id)->orderBy('id', 'desc')->first();
        return view('products.prod-price-adjust', compact('rate'));
    }

    public function adjustPrices(Request $request){
        $validator = Validator::make(
            $request->all(),
            [
                'usd_rate'  => 'required',
            ],
            [
                'usd_rate.required'  => 'The USD rate is mandatory, as it is the default currency alternative.',
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $rate = Prodexchange::create([
            'usd_rate'             => $request->input('usd_rate'),
            'rand_rate'             => $request->input('rand_rate'),
            'rtgs_rate'             => $request->input('rtgs_rate'),
            'changed_by'             => auth()->user()->name,
            'user_id'            => auth()->user()->id,
        ]);

        $rate->save();

        if ($rate->save()){
            $products = Product::where('creator','=', auth()->user()->name)->where('usd_price','!=', null)->get();
            foreach ($products as $product){
                DB::table('products')
                    ->where('id', $product->id)
                    ->update(['price' => number_format($rate->usd_rate*$product->usd_price,2, '.', '')]);
            }
        }

        return redirect()->back()->with('success', 'Rate exchange has been set and applied to all your products successfully.');
    }
}
