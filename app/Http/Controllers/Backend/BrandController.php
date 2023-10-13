<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class BrandController extends Controller
{
    public function allBrand(){
        $brands = Brand::latest()->get();
        return view('backend.brand.brand_all',compact("brands"));

    }

    public function addBrand(){
        return view('backend.brand.brand_add');
    }//end

    public function storeBrand(Request $request){

        $image = $request->file('brand_image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        //pakage image resize
        Image::make($image)->resize(300,300)->save('upload/brand/'.$name_gen);
        $save_url = 'upload/brand/'.$name_gen;

        Brand::insert([
            'brand_name' => $request->brand_name,
            'brand_slug' => strtolower(str_replace(' ', '-',$request->brand_name)),
            'brand_image' => $save_url,
        ]);

        $notification = array(
            'message' => 'Brand Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.brand')->with($notification);

    } //end


    public function editBrand($id){
        $brand = Brand::findOrFail($id);
        return view('backend.brand.brand_edit',compact('brand'));
    }
    //end


    public function updateBrand(Request $request){

        $brand_id = $request->id;
        $old_image = $request->image;

        if ($request->file('brand_image')){

            $image = $request->file('brand_image');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            //pakage image resize
            Image::make($image)->resize(300,300)->save('upload/brand/'.$name_gen);
            $save_url = 'upload/brand/'.$name_gen;

            if(file_exists($old_image)){
               // unlink(public_path('upload/admin_images/'.$brand->photo));
                unlink($old_image);
            }

            Brand::findOrFail($brand_id)->update([
                'brand_name' => $request->brand_name,
                'brand_slug' => strtolower(str_replace(' ', '-',$request->brand_name)),
                'brand_image' => $save_url,
            ]);

            $notification = array(
                'message' => 'Brand Updated Name and Photo Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.brand')->with($notification);
        } else{

            Brand::findOrFail($brand_id)->update([
                'brand_name' => $request->brand_name,
                'brand_slug' => strtolower(str_replace(' ', '-',$request->brand_name)),
            ]);

            $notification = array(
                'message' => 'Brand Name Update Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.brand')->with($notification);

        }//end esle

    }//end


    public function deleteBrand($id){
        $brand = Brand::findOrFail($id);
        $img = $brand->brand_image;
        unlink($img);

        Brand::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Brand Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.brand')->with($notification);


    }
}
