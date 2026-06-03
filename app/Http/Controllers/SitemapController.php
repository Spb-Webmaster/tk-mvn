<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Spatie\Sitemap\SitemapGenerator;

class SitemapController extends Controller
{
    public function sitemap():void
    {
        $path = public_path('sitemap.xml');
        SitemapGenerator::create(config('app.url'))->writeToFile($path);

    }


}
