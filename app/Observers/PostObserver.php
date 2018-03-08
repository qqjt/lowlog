<?php

namespace App\Observers;

use App\Post;
use App\Markdown\Markdown;
use Carbon\Carbon;
use Hashids;

class PostObserver
{
    public function creating(Post $post)
    {
        $markdown = new Markdown();
        $post->html = $markdown->convertMarkdownToHtml($post->content);
        $post->excerpt = str_limit(strip_tags($post->html), 140);
        $post->posted_at = Carbon::now()->format('Y-m-d H:i:s');
    }

    public function created(Post $post)
    {
        Post::where('id', $post->id)->update(['hashid'=>Hashids::connection('post')->encode($post->id)]);
    }

    public function updating(Post $post)
    {
        $markdown = new Markdown();
        $post->html = $markdown->convertMarkdownToHtml($post->content);
    }
}