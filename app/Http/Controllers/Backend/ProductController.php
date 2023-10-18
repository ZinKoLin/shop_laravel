<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCateogry;
use App\Models\User;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function AllProduct(){
        $products = Product::latest()->get();
        return view('backend.product.product_all',compact('products'));
    } // End Method

    public function AddProduct(){
        $activeVendors = User::where('status','active')->where('role','vendor')->latest()->get();
        $brands = Brand::latest()->get();
        $categories = Category::latest()->get();
        $sub_categories = SubCateogry::latest()->get();

        return view('backend.product.product_add',compact('brands','categories','sub_categories',
        'activeVendors'));

    }

}
