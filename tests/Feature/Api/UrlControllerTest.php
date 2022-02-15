<?php

namespace Tests\Feature\Api;

use App\Models\Click;
use App\Models\Url;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UrlControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();

        $this->short_url_to_test = 'ABC12';
        $this->original_url_to_test = 'https://www.fullstacklabs.co';

        Url::factory()->count(5)->create();

        $this->url = Url::factory()->create([
            'short_url' => $this->short_url_to_test,
            'original_url' => $this->original_url_to_test,
            'clicks_count' => 10,
        ]);

        Click::factory()->count(5)->create([
            'url_id' => $this->url->id,
        ]);
    }

    public function testLatestUrlsEndpoint()
    {
        $response = $this->get('api/urls/latest');
        $response->assertStatus(200);

        $response->assertSee('data');
        $response->assertSee('id');
        $response->assertSee('short_url');
        $response->assertSee('original_url');
        $response->assertSee('clicks_count');
        $response->assertSee('created_at');
        $response->assertSee('updated_at');
    }
}
