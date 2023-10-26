<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{

    function update($id){
        $validator = validator(request()->all(), [
            'body' => ['required', 'min:10']
        ]);

        if ($validator->fails()) {
            return $validator->getMessageBag();
        }


        $post = Post::find($id);
        if($post == null){
            return 0;
        }

        if(auth()->user()->id != $post->user_id){
            return 0;
        }

        Post::whereId($id)->update([
            'body' => request()->body
        ]);

        return 1;


    }


    function list () {

        $posts = Post::with('user')->latest()->get();
        // $posts = Post::latest()->get();

        return $posts;
    }
    function myposts () {

        // $user = User::with('posts')->select('id', 'name')->whereId($id)->first();
        $user = auth()->user();
        $user->loadMissing('posts');

        // Logger($user);

        return $user->posts;
    }

    function delete($id){
        // return auth()->user();
        $post = Post::find($id);
        if($post == null){
            return 0;
        }

        if(auth()->user()->id != $post->user_id){
            return 0;
        }

        Post::whereId($id)->delete();

        return 1;
    }

    function post($id){
        // Logger($id);
        return Post::with('user')->find($id);
    }


    function create(){
        $validator = validator(request()->all(), [
            'body' => ['required', 'min:10']
        ]);

        if ($validator->fails()) {
            return $validator->getMessageBag();
        }

        $data = [
            'user_id' => Auth::user()->id,
            'body' => request()->body
        ];

        // Logger($data);
        Post::create($data);

        return [
            'status' => 1
        ];
    }
}
