<?php
namespace App\Http\Controllers;
use App\models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class ChangePasswordController extends Controller{
public function changepassword(Request $request){
    $this->validate($request, [
        'password' => ['required', 'string'],
        'new_password' => [
            'required',
            'string',
            'min:8',
            'regex:/[a-z]/',    
            'regex:/[A-Z]/',     
            'regex:/[0-9]/',      
            'regex:/[@$!%*#?&]/', 
        ],
    ]);
    if (!Hash::check($request->password, auth()->user()->password)) {
        return response()->json(['Incorrect current password'], 400);
    }
    else if (Hash::check($request->new_password, auth()->user()->password)) {
        return response()->json(['Your new password should be different from old password'], 400);
    } 
    else {
        auth()->user()->password = Hash::make($request->new_password);
        auth()->user()->setRememberToken(Str::random(60));
        auth()->user()->save();
        return response()->json(['Your password has been successfully changed'], 200);
    }
}
}
