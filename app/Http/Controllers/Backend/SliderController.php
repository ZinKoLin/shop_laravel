<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;
use Image;

class SliderController extends Controller
{
    public function allSlider()
    {
        $sliders = Slider::latest()->get();
        return view('backend.slider.slider_all', compact("sliders"));

    }//end method

    public function addSlider()
    {
        return view('backend.slider.slider_add');
    }

    public function storeSlider(Request $request)
    {

        $image = $request->file('slider_image');
        $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
        //pakage image resize
        Image::make($image)->resize(2376, 807)->save('upload/slider/' . $name_gen);
        $save_url = 'upload/slider/' . $name_gen;

        Slider::insert([
            'slider_title' => $request->slider_title,
            'short_title' => $request->short_title,
            'slider_image' => $save_url,
        ]);

        $notification = array(
            'message' => 'Slider Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.slider')->with($notification);

    } //end

    public function editSlider($id)
    {
        $slider = Slider::findOrFail($id);
        return view('backend.slider.slider_edit', compact("slider"));
    }

    //end


    public function updateSlider(Request $request)
    {

        $slider_id = $request->id;
        $old_image = $request->image;

        if ($request->file('slider_image')) {
            $image = $request->file('slider_image');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            //pakage image resize
            Image::make($image)->resize(2376, 807)->save('upload/slider/' . $name_gen);
            $save_url = 'upload/slider/' . $name_gen;

            if (file_exists($old_image)) {
                // unlink(public_path('upload/admin_images/'.$brand->photo));
                unlink($old_image);
            }

            Slider::findOrFail($slider_id)->update([
                'slider_title' => $request->slider_title,
                'short_title' => $request->short_title,
                'slider_image' => $save_url,
            ]);

            $notification = array(
                'message' => 'Slider Updated Title and Photo Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.slider')->with($notification);
        } else {

            Slider::findOrFail($slider_id)->update([
                'slider_title' => $request->slider_title,
                'short_title' => $request->short_title,
            ]);

            $notification = array(
                'message' => 'Slider Title Update Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.slider')->with($notification);

        }//end esle

    }//end
    //


        public function deleteSlider($id)
        {
            $slider = Slider::findOrFail($id);
            $img = $slider->slider_image;
            unlink($img);

            Slider::findOrFail($id)->delete();

            $notification = array(
                'message' => 'Slider Deleted Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.slider')->with($notification);

            }//end
}
