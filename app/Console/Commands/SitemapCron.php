<?php


namespace App\Console\Commands;
use App\Http\Controllers\SitemapController;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SitemapCron extends Command
{
    /**
     *
     *
     * @var string
     */
    protected $signature = 'sitemap:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start cron - sitemap:cron';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        ini_set('memory_limit', '8192M');

        $s = new SitemapController();
        $s->sitemap();

    }


}
