<?php

namespace App\Console\Commands;

use App\Post;
use Carbon\Carbon;
use Illuminate\Console\Command;

class XzhSubmitBatch extends Command
{
    protected $signature = 'xzh:batch';

    protected $description = '熊掌号历史内容提交';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $posts = Post::whereIsDraft(Post::NOT_IN_DRAFT)
            ->where('created_at', '<=', Carbon::now()->subDay())->where('created_at', '>=', Carbon::now()->subDays(7))
            ->select('hashid')->get();
        $urls = [];
        foreach ($posts as $post){
            $urls[] = route('post.show', ['post'=>$post->hashid]);
        }
        $api = 'http://data.zz.baidu.com/urls?appid='.config('xzh.app_id').'&token='.config('xzh.token').'&type=batch';
        $ch = curl_init();
        $options = array(
            CURLOPT_URL => $api,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => implode("\n", $urls),
            CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
        );
        curl_setopt_array($ch, $options);
        $result = curl_exec($ch);
        $this->alert($result);
    }
}
