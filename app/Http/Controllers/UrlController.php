<?php

namespace App\Http\Controllers;

use App\Http\Requests\UrlStoreRequest;
use App\Models\Click;
use App\Models\Url;
use App\Services\ShortUrlGeneratorService;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\DB;

class UrlController extends Controller
{
    /**
     * Jenssegers Agent
     *
     * @var Agent
     */
    protected $agent;

    /**
     * Click Model
     *
     * @var Click
     */
    protected Click $click;

    /**
     * Url Model
     *
     * @var Url
     */
    protected Url $url;

    /**
     * Url Service
     *
     * @var ShortUrlGeneratorService
     */
    protected ShortUrlGeneratorService $url_service;

    /**
     * Create a new controller instance.
     *
     * @param Agent $agent
     * @param Click $click
     * @param Url $url
     * @param ShortUrlGeneratorService $url_service
     */
    public function __construct(
        Agent                    $agent,
        Click                    $click,
        Url                      $url,
        ShortUrlGeneratorService $url_service
    )
    {
        $this->agent = $agent;
        $this->click = $click;
        $this->url = $url;
        $this->url_service = $url_service;
    }

    public function index(Request $request)
    {
        $urls = $this->url->limit(10)
            ->orderBy('created_at', 'desc')
            ->get();

        $url = $this->url;

        return view('index', compact('url', 'urls'));
    }

    public function store(UrlStoreRequest $request)
    {
        // Validate data
        $validated_data = $request->validated();
        $validated_data['short_url'] = $this->url_service->generateUniqueId();

        // Create url record
        $this->url->create($validated_data);

        return redirect('/');
    }

    public function visit($url)
    {
        // Get url record
        $url = $this->url->where('short_url', $url)->first();

        // Return 404 page if url is not found
        if (!$url) {
            abort(404);
        }

        // Increment clicks count
        $url->increment('clicks_count');

        // Store click record
        $this->click->url_id = $url->id;
        $this->click->browser = $this->agent->browser();
        $this->click->platform = $this->agent->platform();
        $this->click->save();

        return redirect()->away($url->original_url);
    }

    public function show($url)
    {
        // Get url record
        $url = $this->url->where('short_url', $url)->first();

        // Return 404 page if url is not found
        if (!$url) {
            abort(404);
        }

        // Get metrics
        $daily_clicks = $this->click->where('url_id', $url->id)
            ->where('created_at', '>', now()->subDays(30)->endOfDay())
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->get()
            ->map(function ($item) {
                return [date("d", strtotime($item['date'])), $item['count']];
            })
            ->toArray();

        $browsers_clicks = $this->click->where('url_id', $url->id)
            ->select('browser', DB::raw('count(*) as count'))
            ->groupBy('browser')
            ->get()
            ->map(function ($item) {
                return [$item['browser'], $item['count']];
            })
            ->toArray();

        $platform_clicks = $this->click->where('url_id', $url->id)
            ->select('platform', DB::raw('count(*) as count'))
            ->groupBy('platform')
            ->get()
            ->map(function ($item) {
                return [$item['platform'], $item['count']];
            })
            ->toArray();

        return view('show', compact('url', 'browsers_clicks', 'daily_clicks', 'platform_clicks'));
    }
}
