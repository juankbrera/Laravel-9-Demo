<?php

namespace Tests\Feature;

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
        $this->original_url_to_test = 'https://fullstacklabs.co';

        Url::factory()->count(5)->create();

        $this->url = Url::factory()->create([
            'short_url' => $this->short_url_to_test,
            'original_url' => $this->original_url_to_test,
            'clicks_count' => 0
        ]);

        Click::factory()->count(5)->create([
            'url_id' => $this->url->id,
        ]);
    }

    public function testIndexPageReturnsASuccessfulResponse()
    {
        $response = $this->get('/');
        $response->assertStatus(200);

        $response->assertSee('Create a new short URL');
        $response->assertSee('Shorten URL');
        $response->assertSee('Short URL');
        $response->assertSee('Original URL');
        $response->assertSee('Created');
        $response->assertSee('Clicks Count');
        $response->assertSee('Stats');
    }

    public function testShowPageReturnsASuccessfulResponse()
    {
        $response = $this->get('urls/ABC12');
        $response->assertStatus(200);

        $response->assertSee('Stats for');
        $response->assertSee($this->url->short_url);
        $response->assertSee('Original URL: ' . $this->url->original_url);
        $response->assertSee('total clicks');
        $response->assertSee('Platform');
        $response->assertSee('Browsers');
        $response->assertSee('OS X');
        $response->assertSee('Chrome');
    }

    public function testShowPageFailsIfTheUrlIsInvalid()
    {
        $response = $this->get('urls/1E456');
        $response->assertStatus(404);
    }

    public function testVisitPageReturnsASuccessfulResponse()
    {
        $response = $this->get('urls/ABC12');
        $response->assertStatus(200);
    }

    public function testVisitPageFailsIfTheUrlIsInvalid()
    {
        $response = $this->get('urls/1E456');
        $response->assertStatus(404);
    }

    public function testStoreActionReturnsASuccessfulResponse()
    {
        $response = $this->followingRedirects()->post('urls', ['original_url' => $this->original_url_to_test . '/test']);
        $response->assertStatus(200);

        $response->assertSee($this->original_url_to_test . '/test');
    }

    public function testStoreActionFailsIfTheUrlIsInvalid()
    {
        $response = $this->followingRedirects()->post('urls', ['original_url' => 'INVALID']);
        $response->assertStatus(200);

        $response->assertSee('The url is invalid');
    }

    public function testStoreActionFailsIfTheUrlIsNull()
    {
        $response = $this->followingRedirects()->post('urls', ['original_url' => '']);
        $response->assertStatus(200);

        $response->assertSee('The url is required');
    }
}
