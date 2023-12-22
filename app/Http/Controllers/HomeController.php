<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {
        return view('home', [
            'featured' => Post::where('published_at', '<=', Carbon::now())
                ->with('categories')
                ->where('featured' , true)
                ->latest('published_at')
                ->take(3)
                ->get(),
            'latest' => Post::where('published_at', '<=', Carbon::now())
                ->with('categories')
                ->latest('published_at')
                ->take(9)
                ->get(),
        ]);
    }
}
