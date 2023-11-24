<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MultiImg;
use App\Models\Product;
use App\Models\SubCateogry;
use App\Models\User;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function productDetails($id,$slug){
        $product = Product::findOrFail($id);
        $color = $product->product_color;
        $product_color = explode(',',$color);

        $size= $product->product_size;
        $product_size = explode(',',$size);

        $multiImages = MultiImg::where('product_id',$id)->get();

        $cat_id = $product->category_id;
        $relatedProduct = Product::where('category_id',$cat_id)->where('id','!=',$id)->orderBy('id','DESC')->limit(4)->get();

        return view('frontend.product.product_details',compact('product','product_size','product_color','multiImages','relatedProduct'));
    }//end method

    public function indexCategory(){
        $skip_category_0 = Category::skip(0)->first();
        $skip_product_0 = Product::where('status',1)->where('category_id',$skip_category_0->id)->orderBy('id','DESC')->limit(5)->get();

        $skip_category_2 = Category::skip(2)->first();
        $skip_product_2 = Product::where('status',1)->where('category_id',$skip_category_2->id)->orderBy('id','DESC')->limit(5)->get();

        $hot_deals = Product::where('hot_deals',1)->where('discount_price','!=',NULL)->orderBy('id','DESC')->limit(3)->get();
        $special_offer = Product::where('special_offer',1)->orderBy('id','DESC')->limit(3)->get();
        $new_products = Product::where('status',1)->orderBy('id','DESC')->limit(3)->get();

        $special_deals = Product::where('special_deals',1)->orderBy('id','DESC')->limit(3)->get();

        return view('frontend.index',compact('skip_product_2','skip_category_2',
            'skip_product_0','skip_category_0','hot_deals','special_offer','new_products','special_deals'));
    }//end

    public function vendorDetails($id){

        $vendor = User::findOrFail($id);
        $vproduct = Product::where('vendor_id',$id)->get();
        return view('frontend.vendor.vendor_details',compact('vendor','vproduct'));

    }//end

    public function vendorAll(){

        $vendors = User::where('status','active')->where('role','vendor')->orderBy('id','DESC')->get();
        return view('frontend.vendor.vendor_all',compact('vendors'));

    }//catWise

    public function catWiseProduct(Request $request, $id, $slug){

        $products = Product::where('status',1)->where('category_id',$id)->orderBy('id','DESC')->get();
        $categories = Category::orderBy('category_name','ASC')->get();
        $breadcat = Category::where('id',$id)->first();
        $newProduct = Product::orderBy('id','DESC')->limit(3)->get();
        return view('frontend.product.category_view',compact('products','categories','breadcat','newProduct'));

    }//end

    public function subCatWiseProduct(Request $request, $id, $slug){
        $products = Product::where('status',1)->where('subcategory_id',$id)->orderBy('id','DESC')->get();
        $categories = Category::orderBy('category_name','ASC')->get();
        $subbreadcat = SubCateogry::where('id',$id)->first();
        $newProduct = Product::orderBy('id','DESC')->limit(3)->get();
        return view('frontend.product.subcategory_view',compact('products','categories','subbreadcat','newProduct'));
    }


}
