<?php

namespace App\Observers;

use App\Post;
use App\Markdown\Markdown;
use Carbon\Carbon;
use Hashids;
use HtmlParser\ParserDom;
use Sunra\PhpSimple\HtmlDomParser;

class PostObserver
{

    public function saving(Post $post)
    {
        $markdown = new Markdown();
        $post->html = $markdown->convertMarkdownToHtml($post->content);
        $htmlDom = HtmlDomParser::str_get_html($post->html);
        $headings = $htmlDom->find('h1,h2,h3,h4,h5');
        $nav = '<ul>';
        $level = 1;
        foreach ($headings as $key => $heading) {
            $headingHtml = $heading->outertext();
            $curLevel = (int)$headingHtml[2];
            while ($curLevel > $level) {
                $nav .= '<li><ul>';
                $level += 1;
            }
            while ($curLevel < $level) {
                $nav .= '</ul></li>';
                $level -= 1;
            }
            $nav .= '<li>' . $heading->innertext() . '</li>';
        }
        while ($level > 1) {
            $nav .= '</ul></li>';
            $level -= 1;
        }
        while (strpos($nav, '<ul><li><ul><li>') === 0 && strrpos($nav, '</li></ul></li></ul>') === strlen($nav)-20) {
            $nav = substr($nav, 15, -20 );
        }
        $nav .= '</ul>';
        if (str_starts_with($nav, '<ul><li><ul><li>') && str_ends_with($nav, '</li></ul></li></ul>') ) {
            $nav = substr($nav, 8, -10);
        }
        $post->nav = $nav;
    }

    public function creating(Post $post)
    {
        $post->excerpt = str_limit(strip_tags($post->html), 300);
    }

    public function created(Post $post)
    {
        Post::where('id', $post->id)->update(['hashid' => Hashids::connection('post')->encode($post->id)]);
    }

    public function updating(Post $post)
    {
    }
}