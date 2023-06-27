<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DeployForgeTest extends TestCase
{
    public function testDeploy()
    {
        // Mock response /servers
        Http::fake([
            'forge.laravel.com/api/v1/servers' => Http::response([
                'servers' => [
                    [
                        'id' => 694614,
                        'name' => 'laravel-api',
                    ],
                ],
            ]),
        ]);

        // Mock response /servers/{id}/sites
        Http::fake([
            'forge.laravel.com/api/v1/servers/694614/sites' => Http::response([
                'sites' => [
                    [
                        'id' => 2020896,
                        'server_id' => 694614,
                        'name' => 'default',
                        'deployment_url' => 'https://forge.laravel.com/servers/694614/sites/2020896/deploy/http?token=W4gUKA7GI3Mx581Hbbl7eK4Nioqz6A8LHHUmdNZF',
                    ],
                ],
            ]),
        ]);

        // to Execute run()`
        $response = $this->get('/api/deploy');
        
        // verify response
        $response->assertStatus(200);
        $response->assertJson([
            'success' => ['default'],
            'error' => [],
        ]);
    }
}
