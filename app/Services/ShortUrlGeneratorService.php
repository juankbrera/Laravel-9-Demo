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
     * Create a new service instance.
     *
     * @param Url $url
     */
    public function __construct(Url $url)
    {
        $this->url = $url;
    }

    /**
     * Generate unique url id
     *
     * @param int $length
     * @return string
     */
    public function generateUniqueId(int $length = 5): string
    {
        $unique = false;
        $url_id = null;

        while (!$unique) {
            // Generate random url id
            $url_id = Str::upper(Str::random($length));

            // Check if url id is unique in the database
            $unique = !$this->url->where('short_url', $url_id)->exists();
        }

        return $url_id;
    }
}
