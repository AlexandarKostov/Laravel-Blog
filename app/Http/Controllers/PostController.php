<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        return view('posts.index', [
            'categories' => Category::whereHas('posts', function ($query){
                $query->where('published_at', '<=', Carbon::now());
            })->take(10)->get(),
        ]);
    }

    public function show(Post $post): View
    {
        return \view('posts.show', [
            'post' => $post,
        ]);
    }
}
