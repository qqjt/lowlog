<?php

namespace App\Observers;

use App\Post;
use App\Markdown\Markdown;

class PostObserver
{
    public function creating(Post $post)
    {
        $markdown = new Markdown();
        $post->html = $markdown->convertMarkdownToHtml($post->content);
        $post->excerpt = str_limit(strip_tags($post->html), 140);
    }

    public function updating(Post $post)
    {
        $markdown = new Markdown();
        $post->html = $markdown->convertMarkdownToHtml($post->content);
    }
}