<?php
/**
 * TODO support nested comments
 */
namespace App\Http\Controllers;

use App\Comment;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        //TODO adjust rule according to Auth status
        $this->validate($request, [
            'author_name'=>'string|max:191',
            'email'=>'email|max:191',
            'url'=>'email|max:191',
            'content'=>'required|string',
        ]);
        $comment = new Comment();
        if(\Auth::check()) {
            $comment->author_id = \Auth::user()->id;
            $comment->author_name = \Auth::user()->name;
            $comment->email = \Auth::user()->email;
        } else {
            $comment->author_name = $request->input('author_name');
            $comment->email = $request->input('email');
            $comment->url = $request->input('url');
        }
        $comment->post_id = $post->id;
        if ($request->has('parent_hashid')){
            $parentComment = Comment::where('hashid', $request->input('parent_hashid'))->first();
            if($parentComment!==null)
                $comment->parent_id = $parentComment->id;
        }
        $comment->content = $request->input('content');
        try {
            $comment->save();
            return ['code'=>0, 'message'=> __('Comment saved.')];
        }catch (\Exception $e) {
            return ['code'=>1, 'message'=> __($e->getMessage())];
        }
    }

    public function load(Request $request, Post $post)
    {
        $perPage = 10;
        $query = $post->comments();
        //Manually create paginator, defaults to the last page.
        $totalCount = $query->count();
        $pageCount = intval(($totalCount - 1) / $perPage) + 1;
        $currentPage = $request->has('page') ? LengthAwarePaginator::resolveCurrentPage() : $pageCount;
        $comments = $query->orderBy('id')->skip(($currentPage - 1) * $perPage)->take($perPage)->get();

        $paginator = new LengthAwarePaginator($comments, $totalCount, $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);
        $paginator->appends($request->except('page'));
        return view('comment.load', ['comments' => $paginator])->render();
    }
}
