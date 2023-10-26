<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Log\Logger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    function register(Request $request)
    {
        $validateUser = Validator::make(
            $request->all(),
            [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'confirmPassword' => 'required|same:password'
            ]
        );

        if ($validateUser->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateUser->errors()
            ], 422);
        }


        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),

        ]);

        $user->roles()
            ->attach(Role::where('name', 'student')->first());

        Auth::login($user);

        return response()->json([
            'status' => true,
            'message' => 'User Created Successfully',
            'user' => $user->loadMissing('roles')
        ], 200);
    }


    public function login(Request $request)
    {
        $validator = validator(request()->all(), [
            'email' => ['required', 'email'],
            'password' => ['required'],
            'remember' => ['required'],
        ]);

        $remember = request('remember');


        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 422);
        }


        $user = User::where('email', request('email'))->first();
        if ($user) {
            if (!$user->is_active) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid User!',
                ], 422);
            }
        }


        if (!Auth::attempt($request->only(['email', 'password']), $remember)) {
            return response()->json([
                'status' => false,
                'message' => 'Email & Password does not match with our record.',
            ], 422);
        }



        $request->session()->regenerate();



        return response()->json([
            'status' => true,
            'message' => 'User Logged In Successfully',
            'user' => auth()->user()->loadMissing('roles')
        ], 200);
    }


    function logout()
    {
        Auth::logout();
        return true;
    }


    function user()
    {
        return request()->user();
    }
}
