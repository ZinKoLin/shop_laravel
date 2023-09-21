<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{



    public function adminLogin(){
        return view('admin.admin_login');
    }
    public function adminDashboard(){
        return view('admin.index');
    }

    public function adminDestroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }

    public function adminProfile(){
        $id = Auth::user()->id;
        $adminData = User::find($id);
        return view('admin.admin_profile_view', compact('adminData'));
    }

    public function adminProfileStore(Request $request){
        $id = Auth::user()->id;
        $adminData = User::find($id);
        $adminData->name = $request->name;
        $adminData->email = $request->email;
        $adminData->phone = $request->phone;
        $adminData->address = $request->address;

        if ($request->file('photo')){
            $file = $request->file('photo');
            @unlink(public_path('upload/admin_images/'.$adminData->photo));
            $filename = date('YmdHi').$file->getClientOriginalName();
            $file->move(public_path('upload/admin_images'),$filename);
            $adminData['photo'] = $filename;

        }

        $adminData->save();
        $notification = array(
            'message' => 'Admin Profile Updated Successfully',
            'alert-type' => 'success'
        );
        return redirect()->back()->with($notification);
    }

    public function adminChangPassword(){
        return view('admin.admin_change_password');
    }

    public function adminUpdatePassword(Request $request){
        $request->validate([
           'old_password' => 'required',
           'new_password' => 'required|confirmed',
        ]);

        //match the old password
        if (!Hash::check($request->old_password, auth::user()->password)){
            return back()->with("error","Old Password not Match!");
        }
        //update new password
        User::whereId(auth()->user()->id)->update([
            'password'=> Hash::make($request->new_password)
        ]);

        return back()->with("status","Password Changed Successfully");
    }


}
