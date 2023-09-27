<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentStoreRequest;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function store(CommentStoreRequest $request): Response
    {
        $comment = Comment::create($request->validated());

        $request->session()->flash('comment.id', $comment->id);

        return redirect()->route('comment.index');
    }

    public function edit(Request $request, Comment $comment): Response
    {
        return view('comment.edit', compact('comment'));
    }

    public function destroy(Request $request, Comment $comment): Response
    {
        $comment->delete();

        return redirect()->route('comment.index');
    }
}
