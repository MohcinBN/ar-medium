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
        if(Auth::user()->hasRole('SuperAdmin')) {
            return Inertia::render('Posts/Index', [
                'posts' => Post::all(),
                'tags' => Tag::all(['id', 'name']),
                'success' => session('success')
            ]);
        }
        return Inertia::render('Posts/Index', [
            'posts' => Post::with('user')->latest()->get(),
            'tags' => Tag::all(['id', 'name']),
            'success' => session('success')
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

        return redirect()->route('posts.index')->with('success', 'Post created successfully');
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

        return redirect()->route('posts.index')->with('success', 'Post updated successfully');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully');
    }

    public function show(Post $post) {
        return Inertia::render('Show', [
            'post' => $post->load(['tags', 'user', 'comments.user']),
            'tags' => Tag::all(['id', 'name'])
        ]);
    }
}
