<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Post;
use App\Models\Tag;
use App\Http\Requests\StorePostRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Http\Requests\UpdatePostRequest;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get(['id', 'title', 'status', 'published_at', 'created_at']);

        return Inertia::render('Posts/Index', [
            'posts' => $posts
        ]);
    }

    public function create()
    {
        $tags = Tag::all(['id', 'name']);

        return Inertia::render('Posts/Create', [
            'tags' => $tags
        ]);
    }

    public function store(StorePostRequest $request, Post $post)
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = Auth::id();
        $validatedData['slug'] = Str::slug($validatedData['title']);
        
        $post = Post::create($validatedData);

        if ($request->has('tags')) {
            $post->tags()->attach($request->tags);
        }

        return Inertia::render('Posts/Index', [
            'success' => 'Post created successfully'
        ]);
    }

    public function edit(Post $post)
    {
        return Inertia::render('Posts/Edit', [
            'post' => $post->load('tags'),
            'tags' => Tag::all(['id', 'name'])
        ]);
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $validatedData = $request->validated();

        $post->update($validatedData);

        if ($request->has('tags')) {
            $post->tags()->sync($request->tags);
        }

        return Inertia::render('Posts/Index', [
            'success' => 'Post updated successfully'
        ]);
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return Inertia::render('Posts/Index', [
            'success' => 'Post deleted successfully'
        ]);
    }
}
