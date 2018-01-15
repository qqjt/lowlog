<?php

namespace App\Markdown;

use Parsedown;
use Purifier;

class Markdown
{
    protected $htmlParser;
    protected $markdownParser;

    public function __construct()
    {
        $this->markdownParser = new Parsedown();
    }

    public function convertMarkdownToHtml($markdown)
    {
        $convertedHtml = $this->markdownParser->setBreaksEnabled(true)->text($markdown);
        $convertedHtml = Purifier::clean($convertedHtml, 'post_content');
        return $convertedHtml;
    }
}
