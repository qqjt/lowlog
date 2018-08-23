<?php

namespace App\Console\Commands;

use App\Post;
use Illuminate\Console\Command;

class FixUploadDir extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:upload-dir';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix upload dir';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $posts = Post::all();
        foreach($posts as $post) {
            $post->html = str_replace('uploads/', 'storage/uploads/', $post->html);
            $post->content = str_replace('uploads/', 'storage/uploads/', $post->content);
            $post->save();
        }
    }
}
