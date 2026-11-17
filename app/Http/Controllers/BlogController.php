<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $query = Blog::where('is_published', true)->orderBy('published_at', 'desc');

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('summary', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $blogs = $query->paginate(12)->withQueryString();
        $categories = Blog::where('is_published', true)->distinct()->pluck('category');

        return view('pages.blogs.index', compact('blogs', 'categories'));
    }

    public function show(string $slug): View
    {
        $blog = Blog::where('slug', $slug)->where('is_published', true)->firstOrFail();
        $recentBlogs = Blog::where('id', '!=', $blog->id)->where('is_published', true)->orderBy('published_at', 'desc')->take(3)->get();

        return view('pages.blogs.show', compact('blog', 'recentBlogs'));
    }
}
