<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class DeployForgeController extends Controller
{
    /**
     * Run the deployment script.
     *
     * @return string
     */
    public function run()
    {
        $servers = $this->getServers();

        foreach ($servers as $server) {
            $sites = $this->getSites($server['id']);

            foreach ($sites as $site) {
                Http::get($site['deployment_url']);
            }
        }
    }

    /**
     * Get the list of servers.
     *
     * @return array
     */
    private function getServers()
    {
        $response = Http::withHeaders($this->getForgeHeaders())
            ->get('https://forge.laravel.com/api/v1/servers')
            ->throw();

        return $response['servers'] ?? [];
    }

    /**
     * Get the list of sites for a given server.
     *
     * @param int $serverId
     * @return array
     */
    private function getSites($serverId)
    {
        $response = Http::withHeaders($this->getForgeHeaders())
            ->get("https://forge.laravel.com/api/v1/servers/{$serverId}/sites")
            ->throw();

        return $response['sites'] ?? [];
    }

    /**
     * Get the Forge API headers.
     *
     * @return array
     */
    private function getForgeHeaders()
    {
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('FORGE_API_TOKEN'),
        ];
    }
}
