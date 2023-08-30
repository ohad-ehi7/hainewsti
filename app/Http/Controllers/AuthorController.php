<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\File;


use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index(Request $request)
    {
        return view('back.pages.home');
    }
    public function logout()
    {
        Auth::guard('web')->logout();
        return redirect()->route('author.login');
    }
    public function ResetForm(Request $request, $token = null)
    {
        $data = [
            'pageTitle' => 'Reset Password'
        ];
        return view('back.pages.auth.reset', $data)->with(['token' => $token, 'email' => $request->email]);
    }
    // public function changeProfilePicture(Request $request)
    // {
    //     $user = User::find(auth('web')->id());
    //     $path = 'back/dist/img/authors/';
    //     $file = $request->file('file');
    //     $old_picture =$user->getAttributes()['picture'];
    //     $file_path =$path.$old_picture;
    //     $new_picture_name = 'AIMG'.$user->id.time().rand(1.100000).'.jpg';
    //     if($old_picture != null && File::exists(public_path($file_path))){
    //       File::delete(public_path($file_path));
    //     }
    //     $upload = $file->move(public_path($path),$new_picture_name);
    //     if($upload){
    //         $user->update([
    //           'picture' =>$new_picture_name
    //         ]);
    //      return response()->json(['status'=>1,'msg'=>'your profile picture has been successfully updated.']);
    //     }else{
    //         return response()->json(['status'=>0,'Something went worng']);
            
    //     }
    // }
    public function changeProfilePicture(Request $request)
{
    $user = User::find(auth()->id()); // Use auth() instead of auth('web')
    $path = 'back/dist/img/authors/';
    
    // Validate if a file was actually uploaded
    if (!$request->hasFile('file')) {
        return response()->json(['status' => 0, 'msg' => 'No file uploaded']);
    }

    $file = $request->file('file');
    $old_picture = $user->picture; // Use the property directly instead of getAttributes()
    $file_path = public_path($path . $old_picture);

    // Generate a more unique name for the new picture
    $new_picture_name = 'AIMG' . $user->id . time() . rand(1, 100000) . '.jpg';

    if ($old_picture != null && File::exists($file_path)) {
        File::delete($file_path);
    }

    // Validate the upload before attempting to move the file
    if ($file->isValid()) {
        $upload = $file->move(public_path($path), $new_picture_name);
        if ($upload) {
            $user->update([
                'picture' => $new_picture_name
            ]);
            return response()->json(['status' => 1, 'msg' => 'Your profile picture has been successfully updated.']);
        }
    }

    return response()->json(['status' => 0, 'msg' => 'Something went wrong while uploading the file.']);
}

}


