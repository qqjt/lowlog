<?php

namespace App\Http\Controllers;

use App\Post;
use App\Tag;
use function foo\func;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['author', 'tags']);
        if($request->has('tags')) {
            $tags = explode(',', $request->input('tags'));
            if (!empty($tags)){
                foreach($tags as $tagValue){
                    $query = $query->whereHas('tags', function($q) use ($tagValue){
                        $q->where('tag_value', $tagValue);
                    });
                }
            }
        }
        $paginatedPosts = $query->paginate(10);
        return view('post.index', ['paginatedPosts' => $paginatedPosts]);
    }

    public function create()
    {
        return view('post.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required'
        ]);
        try {
            \DB::beginTransaction();
            $post = new Post();
            $post->title = $request->input('title');
            $post->content = $request->input('content');
            $post->author_id = \Auth::user()->id;
            $post->save();
            //handle tags
            if (!empty($request->input('tags'))) {
                $existedTags = Tag::whereIn('tag_value', $request->input('tags'))->get();
                $tagIds = $existedTags->pluck('id')->toArray();
                $newTagValues = array_diff($request->input('tags'), $existedTags->pluck('tag_value')->toArray());
                if (!empty($newTagValues)) {
                    foreach ($newTagValues as $newTagValue) {
                        if ($newTagValue) {
                            $newTag = new Tag();
                            $newTag->tag_value = $newTagValue;
                            $newTag->save();
                            $tagIds[] = $newTag->id;
                        }
                    }
                }
                if (!empty($tagIds))
                    $post->tags()->attach($tagIds);
            }
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return ['code' => 1, 'message' => __($e->getMessage())];
        }
        return ['code' => 0, 'message' => __('Post created.')];
    }


    public function show($hashid)
    {
        $post = Post::where('hashid', $hashid)->with(['comments'=> function($query){
            $query->orderBy('created_at', 'desc');
        }])->withCount('comments')->first();
        if($post===null)
            abort(404, __("Post Not Found."));
        return view('post.show', ['post' => $post]);
    }

    public function edit(Post $post)
    {
        return view('post.edit', ['post' => $post]);
    }

    public function update(Request $request, Post $post)
    {
        $this->validate($request, [
            'title' => 'required|string|max:191',
            'content' => 'nullable'
        ]);
        try {
            \DB::beginTransaction();
            $post->title = $request->input('title');
            $post->content = $request->input('content');
            $post->save();
            $tagIds = [];
            if (!empty($request->input('tags'))) {
                $existedTags = Tag::whereIn('tag_value', $request->input('tags'))->get();
                $tagIds = $existedTags->pluck('id')->toArray();
                $newTagValues = array_diff($request->input('tags'), $existedTags->pluck('tag_value')->toArray());
                if (!empty($newTagValues)) {
                    foreach ($newTagValues as $newTagValue) {
                        if ($newTagValue) {
                            $newTag = new Tag();
                            $newTag->tag_value = $newTagValue;
                            $newTag->save();
                            $tagIds[] = $newTag->id;
                        }
                    }
                }
            }
            $post->tags()->sync($tagIds);
            \DB::commit();
            return ['code' => 0, 'message' => __('Post updated.')];
        } catch (\Exception $e) {
            \DB::rollBack();
            return ['code' => 1, 'message' => __($e->getMessage())];
        }
    }

    public function destroy(Post $post)
    {
        //
    }
}
