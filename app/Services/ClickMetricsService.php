<?php

namespace App\Services;

use App\Models\Click;
use App\Models\Url;
use Illuminate\Support\Facades\DB;

class ClickMetricsService
{
    /**
     * Click Model
     *
     * @var Click
     */
    protected Click $click;

    /**
     * Create a new service instance.
     *
     * @param Click $click
     */
    public function __construct(Click $click)
    {
        $this->click = $click;
    }

    /**
     * Get url daily clicks for the current month
     *
     * @param Url $url
     * @return array
     */
    public function getDailyClicks(Url $url): array
    {
        return $this->click->where('url_id', $url->id)
            ->whereRaw('MONTH(created_at) = ?', [date('m')])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->get()
            ->map(function ($item) {
                return [date("d", strtotime($item['date'])), $item['count']];
            })
            ->toArray();
    }

    /**
     * Get url clicks by browser for the current month
     *
     * @param Url $url
     * @return array
     */
    public function getBrowserClicks(Url $url): array
    {
        return $this->click->where('url_id', $url->id)
            ->whereRaw('MONTH(created_at) = ?', [date('m')])
            ->select('browser', DB::raw('count(*) as count'))
            ->groupBy('browser')
            ->get()
            ->map(function ($item) {
                return [$item['browser'], $item['count']];
            })
            ->toArray();
    }

    /**
     * Get url clicks by browser for the current month
     *
     * @param Url $url
     * @return array
     */
    public function getPlatformClicks(Url $url): array
    {
        return $this->click->where('url_id', $url->id)
            ->whereRaw('MONTH(created_at) = ?', [date('m')])
            ->select('platform', DB::raw('count(*) as count'))
            ->groupBy('platform')
            ->get()
            ->map(function ($item) {
                return [$item['platform'], $item['count']];
            })
            ->toArray();
    }
}
