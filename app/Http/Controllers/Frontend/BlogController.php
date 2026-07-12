<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\City;
use App\Models\Category;
use App\Support\BlogLayout;

class BlogController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $directory = app()->bound('currentDirectory') ? app('currentDirectory') : null;

        $query = Post::published()->with('directories');
        if ($directory) {
            $query->whereHas('directories', fn($q) => $q->where('directory_id', $directory->id));
        }
        if ($request->filled('q')) {
            $term = $request->string('q')->trim()->toString();
            $query->where(fn($q) => $q->where('title', 'like', "%{$term}%")->orWhere('excerpt', 'like', "%{$term}%"));
        }
        $posts = $query->latest('published_at')->paginate(9)->withQueryString();
        $blogLayout = BlogLayout::normalize($directory?->blog_layout);

        return view('frontend.blog.index', compact('posts', 'directory', 'blogLayout'));
    }

    public function show(string $slug)
    {
        $directory = app()->bound('currentDirectory') ? app('currentDirectory') : null;

        $postQuery = Post::published()->where('slug', $slug);
        if ($directory) {
            $postQuery->whereHas('directories', fn($q) => $q->where('directory_id', $directory->id));
        }
        $post = $postQuery->firstOrFail();

        $relatedPosts = Post::published()
            ->whereHas('directories', fn($q) =>
                $q->whereIn('directory_id', $post->directories->pluck('id'))
            )
            ->where('id', '!=', $post->id)
            ->latest('published_at')
            ->take(3)
            ->get();
        $targetCity = $post->target_city_slug ? City::where('slug', $post->target_city_slug)->first() : null;
        $targetCategory = $post->target_category_slug ? Category::active()->where('slug', $post->target_category_slug)->first() : null;
        $blogLayout = BlogLayout::normalize($directory?->blog_layout);

        return view('frontend.blog.show', compact(
            'post', 'relatedPosts', 'directory', 'blogLayout', 'targetCity', 'targetCategory'
        ));
    }
}
