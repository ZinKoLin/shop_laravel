<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Image;
class BannerController extends Controller
{
    public function allBanner(){
        $banners = Banner::latest()->get();
        return view('backend.banner.banner_all',compact('banners'));
    }//end

    public function addBanner(){
        return view('backend.banner.banner_add');
    }//end

    public function storeBanner(Request $request){
        $image = $request->file('banner_image');
        $name_gen = date('YmdHi').$image->getClientOriginalName();
        //pakage image resize
        Image::make($image)->resize(768,450)->save('upload/banner/'.$name_gen);
        $save_url = 'upload/banner/'.$name_gen;
        Banner::insert([
            'banner_title' => $request->banner_title,
            'banner_url' => strtolower(str_replace(' ', '-',$request->banner_title)),
            'banner_image' => $save_url,
        ]);

        $notification = array(
            'message' => 'Bannner Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.banner')->with($notification);
    }//end

    public function editBanner($id){
        $banner = Banner::findOrFail($id);
        return view('backend.banner.banner_edit',compact('banner'));
    }//

    public function updateBanner(Request $request){
        $banner_id = $request->id;
        $old_image = $request->old_image;
        if ($request->file('banner_image')){
            $image = $request->file('banner_image');
            $name_gen = date('YmdHi').$image->getClientOriginalName();
            //pakage image resize
            Image::make($image)->resize(768,450)->save('upload/banner/'.$name_gen);
            $save_url = 'upload/banner/'.$name_gen;
           if(file_exists($old_image)){
               unlink($old_image);
           }

            Banner::findOrFail($banner_id)->update([
                'banner_title' => $request->banner_title,
                'banner_url' =>$request->banner_url,
                'banner_image' => $save_url,
            ]);

            $notification = array(
                'message' => 'Banner Updated with image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.banner')->with($notification);
        }else{
            Banner::findOrFail($banner_id)->update([
                'banner_title' => $request->banner_title,
                'banner_url' =>$request->banner_url,
            ]);

            $notification = array(
                'message' => 'Banner Updated without image Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.banner')->with($notification);


        }//end else
    }// end method

    public function deleteBanner($id){

        $banner = Banner::findOrFail($id);
        $img = $banner->banner_image;
        unlink($img );

        Banner::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Banner Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);

    }// End Method
}
