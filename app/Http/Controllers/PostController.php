<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use App\Tag;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $perPage = 10;
        $query = Post::whereIsDraft(Post::NOT_IN_DRAFT)->with(['author', 'tags', 'categories'])->orderBy('posted_at');
        if ($request->has('tags')) {
            $tags = explode(',', $request->input('tags'));
            if (!empty($tags)) {
                foreach ($tags as $tagValue) {
                    $query = $query->whereHas('tags', function ($q) use ($tagValue) {
                        $q->where('tag_value', $tagValue);
                    });
                }
            }
        }
        if ($request->has('cat')) {
            $cats = explode(',', $request->input('cat'));
            if (!empty($cats)) {
                foreach ($cats as $categoryName) {
                    $query = $query->whereHas('categories', function ($q) use ($categoryName) {
                        $q->where('name', $categoryName);
                    });
                }
            }
        }
        //Manually create paginator, defaults to the last page.
        $totalCount = $query->count();
        $pageCount = intval(($totalCount - 1) / $perPage) + 1;
        $currentPage = $request->has('page') ? LengthAwarePaginator::resolveCurrentPage() : $pageCount;
        $posts = $query->skip(($currentPage - 1) * $perPage)->take($perPage)->get();
        $posts = $posts->sortByDesc('posted_at');

        $paginator = new LengthAwarePaginator($posts, $totalCount, $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);
        $paginator->appends($request->except('page'));

        return view('post.index', ['paginatedPosts' => $paginator]);
    }

    public function create()
    {
        $categories = Category::all();
        return view('post.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:191',
            'content' => 'required|string',
            'posted_at' => 'nullable|date_format:Y-m-d H:i:s'
        ]);
        try {
            \DB::beginTransaction();
            $post = new Post();
            $post->title = $request->input('title');
            $post->content = $request->input('content');
            $post->posted_at = $request->input('posted_at') ?: Carbon::now()->format('Y-m-d H:i:s');
            $post->author_id = \Auth::user()->id;
            if ($request->has('is_draft')){
                $post->is_draft = 1;
                $post->posted_at = null;
            }
            $post->save();
            //handle tags
            if (!empty($request->input('tags'))) {
                $tagsArr = [];
                $tagValues = [];
                foreach ($request->input('tags') as $displayName) {
                    $displayName = trim($displayName);
                    $tagValue = strtolower($displayName);
                    $tagsArr[$tagValue] = $displayName;
                    $tagValues[] = $tagValue;
                }
                $existedTags = Tag::whereIn('tag_value', $tagValues)->get();
                $tagIds = $existedTags->pluck('id')->toArray();
                $newTagValues = array_diff($tagValues, $existedTags->pluck('tag_value')->toArray());
                if (!empty($newTagValues)) {
                    foreach ($newTagValues as $newTagValue) {
                        if ($newTagValue) {
                            $newTag = new Tag();
                            $newTag->tag_value = $newTagValue;
                            $newTag->display_name = $tagsArr[$newTagValue];
                            $newTag->save();
                            $tagIds[] = $newTag->id;
                        }
                    }
                }
                if (!empty($tagIds))
                    $post->tags()->attach($tagIds);
            }
            //category
            if ($request->has('categories'))
                $post->categories()->sync($request->input('categories'));
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return ['code' => 1, 'message' => __($e->getMessage())];
        }
        return ['code' => 0, 'message' => __('Post created.')];
    }

    public function edit(Post $post)
    {
        $categories = Category::all();
        return view('post.edit', compact('post', 'categories'));
    }

    public function update(Request $request, Post $post)
    {
        $this->validate($request, [
            'title' => 'required|string|max:191',
            'content' => 'required|string',
            'excerpt' => 'required|string|max:240',
            'posted_at' => 'required|date_format:Y-m-d H:i:s'
        ]);
        try {
            \DB::beginTransaction();
            $post->title = $request->input('title');
            $post->content = $request->input('content');
            $post->excerpt = $request->input('excerpt');
            $post->posted_at = $request->input('posted_at');
            if ($request->has('is_draft')){
                $post->posted_at = null;
                $post->is_draft = 1;
            }
            else
                $post->is_draft = 0;
            $post->save();
            $tagIds = [];
            if (!empty($request->input('tags'))) {
                $tagsArr = [];
                $tagValues = [];
                foreach ($request->input('tags') as $displayName) {
                    $displayName = trim($displayName);
                    $tagValue = strtolower($displayName);
                    $tagsArr[$tagValue] = $displayName;
                    $tagValues[] = $tagValue;
                }
                $existedTags = Tag::whereIn('tag_value', $tagValues)->get();
                $tagIds = $existedTags->pluck('id')->toArray();
                $newTagValues = array_diff($tagValues, $existedTags->pluck('tag_value')->toArray());
                if (!empty($newTagValues)) {
                    foreach ($newTagValues as $newTagValue) {
                        if ($newTagValue) {
                            $newTag = new Tag();
                            $newTag->tag_value = $newTagValue;
                            $newTag->display_name = $tagsArr[$newTagValue];
                            $newTag->save();
                            $tagIds[] = $newTag->id;
                        }
                    }
                }
            }
            $post->tags()->sync($tagIds);
            $post->categories()->sync($request->input('categories'));
            \DB::commit();
            return ['code' => 0, 'message' => __('Post updated.'), 'data' => route('post.preview', [$post])];
        } catch (\Exception $e) {
            \DB::rollBack();
            return ['code' => 1, 'message' => __($e->getMessage())];
        }
    }

    public function destroy(Post $post)
    {
        //
    }

    public function show($hashid)
    {
        $post = Post::whereHashid($hashid)->whereIsDraft(Post::NOT_IN_DRAFT)->with(['comments' => function ($query) {
            $query->orderBy('created_at');
        }, 'categories', 'tags'])->withCount('comments')->first();
        if ($post === null)
            abort(404, __("Post Not Found."));
        $post->html = cdn_replace($post->html);
        return view('post.show', ['post' => $post]);
    }

    public function search(Request $request)
    {
        $q = $request->get('q');
        $paginatedPosts = [];
        if ($q) {
            $paginatedPosts = Post::search($q)->paginate();
        }
        return view('post.search', compact('paginatedPosts', 'q'));
    }

    public function preview($hashid)
    {
        $post = Post::whereHashid($hashid)->with(['comments' => function ($query) {
            $query->orderBy('created_at');
        }])->withCount('comments')->first();
        if ($post === null)
            abort(404, __("Post Not Found."));
        //load page default comments(the last page), for ajax loading comments refer to load() in CommentController
        $perPage = 10;
        $query = $post->comments();
        $totalCount = $post->comments_count;
        $pageCount = intval(($totalCount - 1) / $perPage) + 1;
        $currentPage = $pageCount;
        $comments = $query->orderBy('id')->skip(($currentPage - 1) * $perPage)->take($perPage)->get();

        $paginator = new LengthAwarePaginator($comments, $totalCount, $perPage, $currentPage, [
            'path' => route('comment.load', ['post' => $post->hashid]),
        ]);
        return view('post.preview', ['post' => $post, 'comments' => $paginator]);
    }
}
