<?php

namespace App\Http\Controllers;

use App\Http\Requests\BlogStoreRequest;
use App\Http\Requests\BlogUpdateRequest;
use App\Models\Blog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): Response
    {
        $blogs = Blog::all();

        return view('blog.index', compact('blogs'));
    }

    public function create(Request $request): Response
    {
        return view('blog.create');
    }

    public function store(BlogStoreRequest $request): Response
    {
        $blog = Blog::create($request->validated());

        $request->session()->flash('blog.id', $blog->id);

        return redirect()->route('blog.index');
    }

    public function show(Request $request, Blog $blog): Response
    {
        return view('blog.show', compact('blog'));
    }

    public function edit(Request $request, Blog $blog): Response
    {
        return view('blog.edit', compact('blog'));
    }

    public function update(BlogUpdateRequest $request, Blog $blog): Response
    {
        $blog->update($request->validated());

        $request->session()->flash('blog.id', $blog->id);

        return redirect()->route('blog.index');
    }

    public function destroy(Request $request, Blog $blog): Response
    {
        $blog->delete();

        return redirect()->route('blog.index');
    }
}
