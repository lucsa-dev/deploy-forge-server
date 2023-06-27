<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class DeployForgeController extends Controller
{
    /**
     * Run the deployment Forge servers.
     *
     * @return string
     */
    public function run(Request $request)
    {
        Log::info('DeployForgeController@run $request', $request);
        $servers = $this->ForgeApiRequest('servers')['servers'];
        $successSites = [];
        $errorSites = [];
    
        foreach ($servers as $server) {
            $sites = $this->ForgeApiRequest('servers/' . $server['id'] . '/sites')['sites'];
    
            foreach ($sites as $site) {
                try {
                    Http::get($site['deployment_url']);
                    $successSites[] = $site['name'];
                } catch (\Exception $e) {
                    $errorSites[] = [
                        'name' => $site['name'],
                        'error' => $e->getMessage()
                    ];
                }
            }
        }
        return [
            'success' => $successSites,
            'error' => $errorSites
        ];
    }

        /**
     * Forge Api request.
     *
     * @return array
     */
    private function ForgeApiRequest($uri)
    {
        $key = 'forge_api_request'; //Identification key for rate limit
        $maxAttempts = 60; // Limit request per minute
        $decaySeconds = 60; // Waiting time in seconds before resetting the counter
    
        if (!RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            RateLimiter::hit($key, $decaySeconds);
    
            $response = Http::withHeaders($this->getForgeHeaders())
                ->get('https://forge.laravel.com/api/v1/' . $uri)
                ->throw();
    
            return $response;
        } else {
            $timeToWait = RateLimiter::availableIn($key);
    
            if ($timeToWait > 0) {
                sleep($timeToWait);
            }
    
            return $this->ForgeApiRequest($uri); // Retry the request
        }
    }

    /**
     * Get the Forge API headers.
     *
     * @return array
     */
    private function getForgeHeaders()
    {
        $forgeApiToken = env('FORGE_API_TOKEN');
    
        if (empty($forgeApiToken)) {
            throw new \Exception('FORGE_API_TOKEN not configured in .env file');
            }
    
        return [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $forgeApiToken,
        ];
    }
}
