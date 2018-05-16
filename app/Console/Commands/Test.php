<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Analytics;
use Spatie\Analytics\Period;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        \Log::info(date('Y-m-d H:i:s') .': hello world!');
        $this->info('hello world!');
        $analyticsData = Analytics::fetchVisitorsAndPageViews(Period::days(7));
        \Log::debug($analyticsData);
    }
}
