<?php

namespace App\Observers;

use App\Comment;
use App\Markdown\Markdown;
use App\User;
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
        //handle comment hashid
        Comment::where('id', $comment->id)->update(['hashid'=>Hashids::connection('comment')->encode($comment->id)]);
        //associate comment author, create new if not existed.
        if (!$comment->author_id) {
            $author = User::where('email', $comment->email)->first();
            if ($author===null) {
                $comment->author_id = $author->id;
                $comment->save();
            } else {
                $author = new User();
                $author->email = $comment->email;
                $author->save();
            }
        }
    }

    public function updating(Comment $comment)
    {
        $markdown = new Markdown();
        $comment->html = $markdown->convertMarkdownToHtml($comment->content);
    }
}