<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

class PageController extends Controller
{

    public function index()
    {
        return view('welcome');
    }

    public function about()
    {
        return view('about');
    }

    public function archive()
    {
        $posts = Post::select(['title', 'posted_at'])->withCount('comments')->orderBy('posted_at', 'desc')->get();
        $archive = [];
        foreach ($posts as $post) {
            $year = $post->posted_at->format('Y');
            $month = $post->posted_at->format('m');
            $day = $post->posted_at->format('d');
            if (!array_key_exists($year, $archive)) {
                $archive[$year] = ['count' => 0];
            }
            if (!array_key_exists($month, $archive[$year])) {
                $archive[$year][$month] = ['count' => 0];
            }
            if (!array_key_exists($day, $archive[$year][$month])) {
                $archive[$year][$month][$day] = ['count' => 0, 'posts' => []];
            }
            $archive[$year]['count'] += 1;
            $archive[$year][$month]['count'] += 1;
            $archive[$year][$month][$day]['count'] += 1;
            $archive[$year][$month][$day]['posts'][] = $post;
        }
        return view('archive', compact('archive'));
    }
}
