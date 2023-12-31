<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request, $id){
        $product = Product::findOrFail($id);

        if($product->discount_price ==NULL){
            Cart::add([
                'id' => $id,
                'name' => $request->product_name,
                'qty' => $request->quantity,
                'price'=>$product->selling_price,
                'weight'=>1,
                'options' => [
                    'image'=> $product->product_thambnail,
                    'color'=>$request->color,
                    'size'=>$request->size,
                ]
            ]);
            return response()->json(['success'=>'successfully Added on Your Cart']);

        }else{
            Cart::add([
                'id' => $id,
                'name' => $request->product_name,
                'qty' => $request->quantity,
                'price'=>$product->discount_price,
                'weight'=>1,
                'options' => [
                    'image'=> $product->product_thambnail,
                    'color'=>$request->color,
                    'size'=>$request->size,
                ]
            ]);
            return response()->json(['success'=>'successfully Added on Your Cart']);

        }
    }//end

    public function addMiniCart(){

        $carts = Cart::content();
        $cartQty = Cart::Count();
        $cartTotal = Cart::total();

        return response()->json(array(
            'carts' => $carts,
            'cartQty'=> $cartQty,
            'cartTotal'=>$cartTotal
        ));

    }//end


    public function removeMiniCart($rowId){
        Cart::remove($rowId);
        return response()->json(['success' => 'Product Remove From Cart']);

    }// End


    public function addToCartDetails(Request $request, $id){

        $product = Product::findOrFail($id);

        if ($product->discount_price == NULL) {

            Cart::add([

                'id' => $id,
                'name' => $request->product_name,
                'qty' => $request->quantity,
                'price' => $product->selling_price,
                'weight' => 1,
                'options' => [
                    'image' => $product->product_thambnail,
                    'color' => $request->color,
                    'size' => $request->size,
                ],
            ]);

            return response()->json(['success' => 'Successfully Added on Your Cart' ]);

        }else{

            Cart::add([

                'id' => $id,
                'name' => $request->product_name,
                'qty' => $request->quantity,
                'price' => $product->discount_price,
                'weight' => 1,
                'options' => [
                    'image' => $product->product_thambnail,
                    'color' => $request->color,
                    'size' => $request->size,
                ],
            ]);

            return response()->json(['success' => 'Successfully Added on Your Cart' ]);

        }

    }// End Method
}
