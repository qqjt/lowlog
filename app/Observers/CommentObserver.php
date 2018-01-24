<?php

namespace App\Observers;

use App\Comment;
use App\Markdown\Markdown;
use Hashids;

class CommentObserver
{
    public function creating(Comment $comment)
    {
        $markdown = new Markdown();
        $comment->html = $markdown->convertMarkdownToHtml($comment->content);
    }

    public function created(Comment $comment)
    {
        Comment::where('id', $comment->id)->update(['hashid'=>Hashids::connection('comment')->encode($comment->id)]);
    }

    public function updating(Comment $comment)
    {
        $markdown = new Markdown();
        $comment->html = $markdown->convertMarkdownToHtml($comment->content);
    }
}