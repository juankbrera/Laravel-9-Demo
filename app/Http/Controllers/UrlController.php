<?php

namespace App\Http\Controllers;

use App\Http\Requests\UrlStoreRequest;
use App\Models\Click;
use App\Models\Url;
use App\Services\ClickMetricsService;
use App\Services\ShortUrlGeneratorService;
use App\Services\UrlService;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class UrlController extends Controller
{
    /**
     * Jenssegers Agent
     *
     * @var Agent
     */
    protected Agent $agent;

    /**
     * Click Model
     *
     * @var Click
     */
    protected Click $click;

    /**
     * Click Metrics Service
     *
     * @var ClickMetricsService
     */
    protected ClickMetricsService $click_metrics_service;

    /**
     * Url Model
     *
     * @var Url
     */
    protected Url $url;

    /**
     * Url Service
     *
     * @var UrlService
     */
    protected UrlService $url_service;

    /**
     * Short Url Service
     *
     * @var ShortUrlGeneratorService
     */
    protected ShortUrlGeneratorService $short_url_generator_service;

    /**
     * Generate a new service instance
     *
     * @param Agent $agent
     * @param Click $click
     * @param Url $url
     * @param UrlService $url_service
     */
    public function __construct(
        Agent                    $agent,
        Click                    $click,
        ClickMetricsService      $click_metrics_service,
        Url                      $url,
        UrlService               $url_service,
        ShortUrlGeneratorService $short_url_generator_service
    )
    {
        $this->agent = $agent;
        $this->click = $click;
        $this->click_metrics_service = $click_metrics_service;
        $this->url = $url;
        $this->url_service = $url_service;
        $this->short_url_generator_service = $short_url_generator_service;
    }

    public function index()
    {
        $urls = $this->url_service->getLatest();
        return view('index', compact('urls'));
    }

    public function store(UrlStoreRequest $request)
    {
        // Getting validated data
        $validated_data = $request->validated();
        $validated_data['short_url'] = $this->short_url_generator_service->generate();

        // Create url record
        $this->url->create($validated_data);

        return redirect('/');
    }

    public function visit(string $short_url)
    {
        // Get url record
        $url = $this->url_service->getByShortUrl($short_url);

        // Show 404 page if the url is not found
        if (!$url) {
            abort(404);
        }

        // Increment clicks count
        $this->url_service->incrementUrlClicksCount($url);

        // Store click record
        $this->click->url_id = $url->id;
        $this->click->browser = $this->agent->browser();
        $this->click->platform = $this->agent->platform();
        $this->click->save();

        return redirect()->away($url->original_url);
    }


    public function show(string $short_url)
    {
        // Get url record
        $url = $this->url_service->getByShortUrl($short_url);

        // Show 404 page if the url is not found
        if (!$url) {
            abort(404);
        }

        // implement queries
        $daily_clicks = $this->click_metrics_service->getDailyClicks($url);
        $browsers_clicks = $this->click_metrics_service->getBrowserClicks($url);
        $platform_clicks = $this->click_metrics_service->getPlatformClicks($url);

        return view('show', compact('url', 'browsers_clicks', 'daily_clicks', 'platform_clicks'));
    }
}
