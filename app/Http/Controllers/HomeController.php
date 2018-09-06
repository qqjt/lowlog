<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $myPosts = Post::whereAuthorId(\Auth::user()->id)->with(['tags'])->orderByDesc('is_draft')->orderBy('posted_at', 'desc')->paginate(10);
        return view('home', ['myPosts' => $myPosts]);
    }
}
