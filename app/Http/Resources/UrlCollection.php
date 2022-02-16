<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UrlResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'type' => 'urls',
            'id' => strval($this->id),
            'attributes' => [
                'short_url' => $this->short_url,
                'original_url' => $this->original_url,
                'clicks_count' => $this->clicks_count,
                'created_at' => $this->created_at,
            ],
        ];
    }
}
