<?php

namespace App;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;

/**
 * App\Post
 *
 * @property int $id
 * @property int $author_id
 * @property string $title
 * @property string|null $excerpt
 * @property string $content
 * @property string|null $html
 * @property string|null $hashid
 * @property string|null $deleted_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\User $author
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Tag[] $tags
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Post onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereExcerpt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereHashid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereHtml($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Post withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Post withoutTrashed()
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Comment[] $comments
 * @property string|null $nav
 * @property \Carbon\Carbon|null $posted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereNav($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post wherePostedAt($value)
 * @property string|null $toc
 * @property int|null $is_draft
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereIsDraft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Post whereToc($value)
 */
class Post extends Model implements Feedable
{
    use SoftDeletes;
    use Searchable;

    const IN_DRAFT = 1;
    const NOT_IN_DRAFT = 0;

    protected $dates = ['created_at', 'updated_at', 'deleted_at', 'posted_at'];

    /**
     * Alter model binding key.
     *
     * @return string
     *
     */
    public function getRouteKeyName()
    {
        return 'hashid';
    }

    // Feed
    public function toFeedItem()
    {
        return FeedItem::create([
            'id' =>$this->hashid,
            'title' =>$this->title,
            'summary' =>$this->excerpt,
            'content' => $this->html,
            'updated' =>$this->posted_at,
            'link' =>route('post.show', $this->hashid),
            'author' =>$this->author->name]
        );
    }
    public static function getFeedItems()
    {
        return Post::whereIsDraft(Post::NOT_IN_DRAFT)->orderBy('posted_at', 'desc')->take(10)->get();
    }

    // Relationships
    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'post_category')->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function shouldBeSearchable()
    {
        return $this->is_draft === 0;
    }

    public function toSearchableArray()
    {
        return array_only($this->toArray(), ['id', 'title', 'html']);
    }
}
