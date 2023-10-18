<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SubCateogry;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\SubCategory;

class SubCategoryController extends Controller
{
    public function allSubCategory(){
        $subcategories = SubCateogry::latest()->get();
        return view('backend.subcategory.subcategory_all',compact('subcategories'));
    } // End Method


    public function addSubCategory(){

        $categories = Category::orderBy('category_name','ASC')->get();
        return view('backend.subcategory.subcategory_add',compact('categories'));

    }// End Method


    public function storeSubCategory(Request $request){

        SubCateogry::insert([
            'category_id' => $request->category_id,
            'subcategory_name' => $request->subcategory_name,
            'subcategory_slug' => strtolower(str_replace(' ', '-',$request->subcategory_name)),
        ]);

        $notification = array(
            'message' => 'SubCategory Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.subcategory')->with($notification);

    }// End Method


    public function editSubCategory($id){

        $categories = Category::orderBy('category_name','ASC')->get();
        $subcategory = SubCateogry::findOrFail($id);
        return view('backend.subcategory.subcategory_edit',compact('categories','subcategory'));

    }// End Method



    public function updateSubCategory(Request $request){

        $subcat_id = $request->id;

        SubCateogry::findOrFail($subcat_id)->update([
            'category_id' => $request->category_id,
            'subcategory_name' => $request->subcategory_name,
            'subcategory_slug' => strtolower(str_replace(' ', '-',$request->subcategory_name)),
        ]);

        $notification = array(
            'message' => 'SubCategory Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.subcategory')->with($notification);


    }// End Method


    public function eeleteSubCategory($id){

        SubCateogry::findOrFail($id)->delete();

        $notification = array(
            'message' => 'SubCategory Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);


    }//End Method

    public function getSubCategory($category_id){
        $subcat = SubCateogry::where('category_id',$category_id)->orderBy('subcategory_name','ASC')->get();
        return json_encode($subcat);
    }



}
