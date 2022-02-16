<?php

namespace App\Services;

use App\Models\Url;
use Illuminate\Support\Str;

class ShortUrlGeneratorService
{
    /**
     * Url Model
     *
     * @var Url
     */
    protected Url $url;

    /**
     * Generate a new service instance
     *
     * @param Url $url
     */
    public function __construct(Url $url)
    {
        $this->url = $url;
    }

    /**
     * Generate short url
     *
     * @return string
     */
    public function generate(): string
    {
        $unique = false;
        $short_url = '';

        while (!$unique){
            // Generate random short url
            $short_url = Str::upper(Str::random(5));

            // Check if short url is unique in database
            $unique = !$this->url->where('short_url', $short_url)->exists();
        }

        return $short_url;
    }
}
