<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Course;
use App\Models\Instructor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
// use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    function list()
    {
        return User::with('roles')->get();
    }



    function dashboard()
    {
        $instructor_count = Instructor::count();
        $user_count = User::count();
        $course_count = Course::count();
        return [
            'instructor_count' => $instructor_count,
            'user_count' => $user_count,
            'course_count' => $course_count,
        ];
    }

    public function saveUser(Request $request)
    {
        // echo '<pre>';print_r($_POST);exit;
        $user_id = $request->input('user_id');

        //validation rules
        if ($user_id) {

            $validation_rules = [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'roles' => 'required'
            ];
        } else {

            $validation_rules = [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                // 'password' => 'required|string|min:6',
                'password' => 'required|string',
                'roles' => 'required'
            ];
        }

        $validator = Validator::make($request->all(), $validation_rules);

        // Stop if validation fails
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($user_id) {
            $user = User::find($user_id);
            // Detach all roles for the existing user to update new roles...
            $user->roles()->detach();
            $success_message = 'User updated successfully';
        } else {
            $user = new User();
            $success_message = 'User added successfully';
        }

        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');

        $user->first_name =  $first_name;
        $user->last_name = $last_name;

        $email = $request->input('email');
        if ($email) {
            $user->email = $email;
        }

        $password = $request->input('password');
        if ($password) {
            $user->password = Hash::make($password);
            // Logger('password');
        }


        $user->is_active = $request->input('is_active');
        $user->save();

        $roles = $request->input('roles');
        foreach ($roles as $role_name) {
            $role = Role::where('name', $role_name)->first();
            $user->roles()->attach($role);
        }

        $instructor = $user->instructor;

        if(in_array('instructor', $roles) && !$instructor ){
            // Instructor::create([
            //     'user_id' => $user->id,
            //     'first_name' => '',
            //     'last_name' => '',
            //     'contact_email' => '',
            //     'telephone' => '',
            //     'paypal_id' => '',
            //     'biography' => '',
            // ]);

            $i = new Instructor();
            $i->user_id = $user->id;
            $i->first_name = $first_name;
            $i->last_name = $last_name;
            $i->contact_email = '';
            $i->telephone = '';
            $i->paypal_id = '';
            $i->biography = '';
            $i->instructor_image = 'default-image.jpg';
            $i->save();
        }

        if(!in_array('instructor', $roles) && $instructor){
            $instructor->delete();
        }



        return $user;
    }

    function deleteUser(){
        $user_id = request('user_id');

        $user = User::find($user_id);
        if($user){


            $user->delete();

        }

        return 1;
    }
}
