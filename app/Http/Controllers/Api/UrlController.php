<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UrlCollection;
use App\Models\Url;
use App\Services\UrlService;

class UrlController extends Controller
{
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
     * Create a new controller instance.
     *
     * @param Url $url
     * @param UrlService $url_service
     */
    public function __construct(Url $url, UrlService $url_service)
    {
        $this->url = $url;
        $this->url_service = $url_service;
    }

    /**
     * Return the last 10 urls created
     *
     * @return UrlCollection
     */
    public function latest(): UrlCollection
    {
        return new UrlCollection($this->url_service->getLatest());
    }
}
