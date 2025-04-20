<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomePageController extends Controller
{
    public function index()
    {
        $latestPosts = Post::with('user')
            ->latest()
            ->take(5)
            ->get();

        $staffPicks = Post::with('user')
            ->inRandomOrder()
            ->take(3)
            ->get();

        return Inertia::render('Home', [
            'latestPosts' => $latestPosts,
            'staffPicks' => $staffPicks,
        ]);
    }
}
