<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function showAllUsers(Request $request)
    {
        $sortingparam1 = $request->sort;
        $sortingparam2 = $request->filter;
        // echo $sortingparam2;
        if ($sortingparam1 != NULL) {
            $users = User::where('is_user_active', '=', '1')->orderBy($sortingparam1,'asc')->get();
            return response()->json($users);
        }
        if ($sortingparam2 === 'admin') {
            $users = User::get()->where('role', 1)->where('is_user_active', '=', '1');
            return response()->json($users);
        } else if ($sortingparam2 === 'normal') {
            $users = User::get()->where('role', 2)->where('is_user_active', '=', '1');
            return response()->json($users);
        } 
        else {
            $users = User::where('is_user_active', '=', '1');
            return response()->json($users->get());
        }
    }


    public function create(Request $request)
    {
        $requestData = $request->all();
        $this->validate($request, [
            'name' => 'bail|required|min:5|max:50',
            'email' => 'bail|required|email|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'max:50',             // must be at least 8 characters in length
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*#?&]/', // must contain a special character
            ],
        ]);
        $requestData['role'] = 2;
        if (!$request->creator) {
            $requestData['creator'] = NULL;
        }
        if (!$request->deleted_by) {
            $requestData['deleted_by'] = NULL;
        }
        $opts = ["cost" => 15, "salt" => "saltrandom080820221116"];
        $requestData['password'] = password_hash($requestData['password'], PASSWORD_BCRYPT, $opts);
        $user = User::create($requestData);

        return response()->json($user, 201);
    }

    public function update($id, Request $request)
    {
        $requestData = $request->all();
        $user = User::findOrFail($id);
        $this->validate($request, [
            'name' => 'min:5|max:50',
        ]);
        if ($request['email'] && $request['email'] != $user->email) {
            $this->validate($request, [
                'email' => 'bail|email|unique:users',
            ]);
        }
        unset($requestData['password']);
        unset($requestData['role']);

        $user->update($requestData);
        return response()->json($user, 200);
    }

    public function delete($id)
    {
        $user = User::find($id);
        if ($user == null) {
            return response('User has been already deleted or ', 401);
        }
        $user->is_user_active = 0;
        $user->save();
        return response('Deleted Successfully', 200);
    }
}
