<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostDetailResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        return PostDetailResource::collection($posts->loadMissing(['writer:id,username']));
    }

    public function show($id)
    {
        $post = Post::with('writer:id,username')->findOrFail($id);
        return new PostDetailResource($post);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'news_content' => 'required',
        ]);

        $request['author'] = Auth::user()->id;
        $post = Post::create($request->all());

        return new PostDetailResource($post->loadMissing('writer:id,username'));

    }

    public function update(Request $request, $id) //request ketika menerima inputan
    {
        $validated = $request->validate([
            'title' => 'required | max:255',
            'news_content' => 'required',
        ]);

        $post = Post::findOrFail($id);
        $post->update($validated);
        return new PostDetailResource($post->loadMissing('writer:id,username'));
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return new PostDetailResource($post->loadMissing('writer:id,username'));
    }
}
