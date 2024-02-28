<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'post_id' => 'required | exists:posts,id',
            'comments_content' => 'required',
        ]); //validation from frontend

        $request['user_id'] = Auth::user()->id; //get user id
        $comment = Comment::create($request->all()); //create comment

        return new CommentResource($comment->loadMissing(['commentator:id,username']));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'comments_content' => 'required',
        ]); //validation from frontend

        $comment = Comment::findOrFail($id);
        $comment->update($request->only('comments_content'));
        return new CommentResource($comment->loadMissing(['commentator:id,username']));
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
        return new CommentResource($comment->loadMissing(['commentator:id,username']));
    }
}
