<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostDetailResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function show($id)
    {
        $posts = Post::with('writer:id,username')->findOrFail($id);
        return new PostResource($posts); //penggunaan collection untuk mengembalikan data berupa array
    }

    public function index()
    {
        $post = Post::all(); //pemanggilan variabel yang diinginkan
        return PostDetailResource::collection($post);
    }
}
