<?php

namespace App\Services;

use App\Models\Url;

class UrlService
{
    /**
     * Url Model
     *
     * @var Url
     */
    protected Url $url;

    /**
     * Create a new service instance.
     *
     * @param Url $url
     */
    public function __construct(Url $url)
    {
        $this->url = $url;
    }

    /**
     * Return the last 10 short urls generated
     *
     * @return mixed
     */
    public function getLatest(): mixed
    {
        return $this->url->limit(10)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get url record by short_url
     *
     * @param string $short_url
     * @return mixed
     */
    public function getByShortUrl(string $short_url): mixed
    {
        return $this->url->where('short_url', $short_url)->first();
    }

    /**
     * Increments Clicks Count
     *
     * @param Url $url
     * @return void
     */
    public function incrementUrlClicksCount(Url $url): void
    {
        $this->url->increment('clicks_count');
    }
}
