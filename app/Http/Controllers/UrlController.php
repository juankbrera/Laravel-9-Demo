<?php

namespace App\Http\Controllers;

use App\Models\Url;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class UrlController extends Controller
{
    public function index(Request $request)
    {
        /* $request->session()->flash('notice', 'Task was successful!'); */

        $urls= [
            new Url(["short_url" =>  '1ry23', 'original_url' =>  'http://google.com', 'created_at' => date("Y-m-d H:i:s"), 'clicks_count' => 0]),
            new Url(['short_url' =>  '45126', 'original_url' =>  'http://facebook.com', 'created_at' => date("Y-m-d H:i:s"), 'clicks_count' => 0]),
            new Url(['short_url' =>  '78sk9', 'original_url' =>  'http://yahoo.com', 'created_at' => date("Y-m-d H:i:s"), 'clicks_count' => 0])
        ];

        $url = new Url;

        return view('index', ['url' =>$url, 'urls' => $urls]);
    }

    public function create()
    {
        /* Create a new URL record */
    }

    public function visit($url)
    {
        /* $agent = new Agent(); */
        // $url = find record a clicks, update irl clicks count and redirect to original url
    }


    public function show()
    {
        $url = new Url(
            [
                'short_url' =>  '1ry23', 'original_url' =>  'http://google.com', 'created_at' => date("Y-m-d H:i:s"), 'clicks_count' => 0
            ]
        );

        // implement queries
        $daily_clicks = [
            ['1', 13],
            ['2', 2],
            ['3', 1],
            ['4', 7],
            ['5', 20],
            ['6', 18],
            ['7', 10],
            ['8', 20],
            ['9', 15],
            ['10', 5]
        ];
        $browsers_clicks = [
            ['IE', 13],
            ['Firefox', 22],
            ['Chrome', 17],
            ['Safari', 7]
        ];
        $platform_clicks = [
            ['Windows', 13],
            ['macOS', 22],
            ['Ubuntu', 17],
            ['Other', 7]
        ];

        return view('show', ['url' =>$url, 'browsers_clicks' => $browsers_clicks, 'daily_clicks' => $daily_clicks, 'platform_clicks' => $platform_clicks]);
    }
}
