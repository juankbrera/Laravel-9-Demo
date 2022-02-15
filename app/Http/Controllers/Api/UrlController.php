<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UrlCollection;
use App\Models\Url;

class UrlController extends Controller
{
    /**
     * Url Model
     *
     * @var Url
     */
    protected Url $url;

    /**
     * Create a new controller instance.
     *
     * @param Url $url
     */
    public function __construct(Url $url)
    {
        $this->url = $url;
    }

    /**
     * Return the last 10 urls created
     *
     * @return UrlCollection
     */
    public function latest(): UrlCollection
    {
        return new UrlCollection($this->url->limit(10)->get());
    }
}
