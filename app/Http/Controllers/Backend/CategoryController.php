<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;


class CategoryController extends Controller
{
    public function allCategory(){
        $categories = Category::latest()->get();
        return view('backend.category.category_all',compact("categories"));

    }

    public function addCategory(){
        return view('backend.category.category_add');
    }//end

    public function storeCategory(Request $request){

        $image = $request->file('category_image');
        $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
        //pakage image resize
        Image::make($image)->resize(300,300)->save('upload/category/'.$name_gen);
        $save_url = 'upload/category/'.$name_gen;
        Category::insert([
            'category_name' => $request->category_name,
            'category_slug' => strtolower(str_replace(' ', '-',$request->category_name)),
            'category_image' => $save_url,
        ]);

        $notification = array(
            'message' => 'Category Inserted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('all.category')->with($notification);

    } //end


    public function editCategory($id){
        $category = Category::findOrFail($id);
        return view('backend.category.category_edit',compact('category'));
    }
    //end


    public function updateCategory(Request $request){

        $category_id = $request->id;
        $old_image = $request->image;

        if ($request->file('category_image')){

            $image = $request->file('category_image');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            //pakage image resize
            Image::make($image)->resize(300,300)->save('upload/category/'.$name_gen);
            $save_url = 'upload/category/'.$name_gen;

            if(file_exists($old_image)){
                // unlink(public_path('upload/admin_images/'.$brand->photo));
                unlink($old_image);
            }

            Category::findOrFail($category_id)->update([
                'category_name' => $request->category_name,
                'category_slug' => strtolower(str_replace(' ', '-',$request->category_name)),
                'category_image' => $save_url,
            ]);

            $notification = array(
                'message' => 'Category Updated Name and Photo Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('all.category')->with($notification);
        } else{

            Category::findOrFail($category_id)->update([
                'category_name' => $request->category_name,
                'category_slug' => strtolower(str_replace(' ', '-',$request->category_name)),
            ]);

            $notification = array(
                'message' => 'Category Name Update Successfully',
                'alert-type' => 'success'
            );
            return redirect()->route('all.category')->with($notification);

        }//end esle

    }//end


    public function deleteCategory($id){
        $category = Category::findOrFail($id);
        $img = $category->category_image;
        unlink($img);

        Category::findOrFail($id)->delete();

        $notification = array(
            'message' => 'Category Deleted Successfully',
            'alert-type' => 'success'
        );
        return redirect()->route('all.category')->with($notification);


    }//end

}
