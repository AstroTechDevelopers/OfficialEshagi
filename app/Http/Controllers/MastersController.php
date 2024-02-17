<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;
use jeremykenedy\LaravelRoles\Models\Role;
use Auth;

class MastersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $categories = Category::all();
       return view('backend.masters.category.categories', compact('categories'));
    }
	
	public function getCategories()
    {
       $categories = Category::all();
       return view('backend.masters.category.categories', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function saveCategory(Request $request)
    {
       $category_name = strip_tags($request->input('catname'));
       $category_slug = strip_tags($request->input('catslug'));

       $validator = Validator::make(
          $request->all(),
            [
               'catname' => 'required',
               'catslug' => 'required',        
            ],
            [
               'catname' => 'Category can not be empty',
               'catslug' => 'Category slug can not be empty',
            ]
       );

       if ($validator->fails()) {
          return back()->withErrors($validator)->withInput();
       }

	   $category = Category::create([
          'category_name' => $category_name,
          'category_slug' => $category_slug,
          'material_group_id' => 1,
          'added_by' => auth()->user()->id,
       ]);

       $category->save();
	   
	   return redirect('categories')->with('success', 'Category added successfully');
    }

    /**
     * Show the form for adding the specified resource.
     *
     * @param  \App\Models\Category
     * @return \Illuminate\Http\Response
     */
    public function addCategory()
    {
       return view('backend.masters.category.add-category');
    }
	
	/**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category
     * @return \Illuminate\Http\Response
     */
    public function editCategory($id)
    {
       $category = Category::findOrFail($id)->first();
       return view('backend.masters.category.edit-category', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function updateCategory(Request $request)
    {
       $category_name = $request->input('catname');
       $category_slug = $request->input('catslug');
	   $category_id = $request->input('catid');

       $validator = Validator::make(
          $request->all(),
            [
               'catname' => 'required',
               'catslug' => 'required',        
            ],
            [
               'catname' => 'Category can not be empty',
               'catslug' => 'Category slug can not be empty',
            ]
       );

       if ($validator->fails()) {
          return back()->withErrors($validator)->withInput();
       }
       
       DB::statement("UPDATE product_categories SET category_name='". $category_name ."', category_slug='". $category_slug ."' WHERE id=" . $category_id);

       return redirect()->back()->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return redirect('clients')->with('success', 'Client deleted successfully.');
    }
}