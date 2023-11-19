<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\MultiImg;
use App\Models\Product;
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

        return view('frontend.product.product_details',compact('product','product_size','product_color','multiImages'));
    }
}
