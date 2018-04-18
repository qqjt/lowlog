<?php

namespace App\Observers;

use App\Post;
use App\Markdown\Markdown;
use Hashids;
use Sunra\PhpSimple\HtmlDomParser;

class PostObserver
{

    public function saving(Post $post)
    {
        $markdown = new Markdown();
        $html = $markdown->convertMarkdownToHtml($post->content);

        //add anchor in html && generate TOC
        $htmlDom = HtmlDomParser::str_get_html($html);
        $headings = $htmlDom->find('h2,h3,h4,h5');
        $toc = '<ul>';
        $level = 2;
        $curLevel = 2;
        foreach ($headings as $key => $heading) {
            $headingHtml = $heading->outertext();
            $curLevel = (int)$headingHtml[2];
            $anchor = 'h' . $curLevel . '-' . $key;
            $heading->id = $anchor;
            if ($curLevel == $level) {
                if ($key != 0)
                    $toc .= '</li>';
                $toc .= '<li><a href="#' . $anchor . '">' . $heading->innertext() . '</a>';
            } elseif ($curLevel > $level) {
                $toc .= '</li><ul>';
                $level += 1;
                while ($curLevel > $level) {
                    $toc .= '<li><ul>';
                    $level += 1;
                }
                $toc .= '<li><a href="#' . $anchor . '">' . $heading->innertext() . '</a>';
            } else {
                $toc .= '</li></ul>';
                $level -= 1;
                while ($curLevel < $level) {
                    $toc .= '</li></ul>';
                    $level -= 1;
                }
                $toc .= '<li><a href="#' . $anchor . '">' . $heading->innertext() . '</a>';
            }
        }
        while ($curLevel > $level) {
            $toc .= '</li></ul>';
            $curLevel -= 1;
        }
        $toc .= '</ul>';

        $post->toc = $toc;
        $post->html = (string)$htmlDom;
        $post->excerpt = str_limit(strip_tags($post->html), 300);
    }

    public function creating(Post $post)
    {
    }

    public function created(Post $post)
    {
        Post::where('id', $post->id)->update(['hashid' => Hashids::connection('post')->encode($post->id)]);
    }

    public function updating(Post $post)
    {
    }
}