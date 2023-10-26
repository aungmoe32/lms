<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Instructor;
use App\Models\Role;
use App\Models\User;
use Illuminate\Log\Logger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    function user($id)
    {
        // Logger($id);
        $user = User::with('posts')->select('id', 'name')->whereId($id)->first();
        // Logger($user);
        // $user->loadMissing('posts');
        return $user;
    }




    function profile()
    {
        $user = auth()->user();
        $user->load([
            'roles'
        ]);
        return $user;
    }


    function saveProfile()
    {
        $user = auth()->user();
        $user->first_name = request('first_name');
        $user->last_name = request('last_name');
        $user->email = request('email');
        $password = request('password');
        if ($password) {
            $user->password = Hash::make($password);
        }

        $user->save();

        $user->load([
            'roles'
        ]);
        return $user;
    }


}
