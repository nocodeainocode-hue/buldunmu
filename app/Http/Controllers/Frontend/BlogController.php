<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Post;

class BlogController extends Controller
{
    public function index()
    {
        $directory = app()->bound('currentDirectory') ? app('currentDirectory') : null;

        $query = Post::published()->with('directories');
        if ($directory) {
            $query->whereHas('directories', fn($q) => $q->where('directory_id', $directory->id));
        }
        $posts = $query->latest('published_at')->paginate(9);

        return view('frontend.blog.index', compact('posts', 'directory'));
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

        return view('frontend.blog.show', compact('post', 'relatedPosts', 'directory'));
    }
}
