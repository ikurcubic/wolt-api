<?php

declare(strict_types=1);

namespace IvanKurcubic\WoltAPI;


use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;
use IvanKurcubic\WoltAPI\Exceptions\HttpResponseException;

/**
 *
 */
class Client
{
    const ENV_STAGE = 'stage';
    const ENV_PRODUCTION = 'production';
    private string $username;
    private string $password;
    private string $environment;
    private GuzzleClient $http;

    public function __construct(string $username, string $password, string $environment)
    {
        $this->username = $username;
        $this->password = $password;
        $this->environment = $environment;

        $host = $this->isProductionEnv() ? 'https://pos-integration-service.wolt.com/' : 'https://pos-integration-service.development.dev.woltapi.com/';
        $this->http = new GuzzleClient(['base_uri' => $host]);
    }

    public function isProductionEnv(): bool
    {
        return $this->environment == self::ENV_PRODUCTION;
    }

    /**
     * @param string $method
     * @param string $endpoint
     * @param array $params
     * @return array
     * @throws GuzzleException
     */
    private function request(string $method, string $endpoint, array $params = []): array
    {
        $params['headers'] = ['Accept' => 'application/json'];
        $params['auth'] = [$this->username, $this->password];

        $response = $this->http->request($method, $endpoint, $params);
        if ($response->getStatusCode() >= 400) {
            throw new HttpResponseException($response);
        }
        return json_decode($response->getBody()->getContents(), true);
    }


    /**
     * @param string $venueId
     * @param array $data
     * @return array|null
     * @throws GuzzleException
     */
    public function updateItems(string $venueId, array $data): ?array
    {
        $endpoint = "/venues/$venueId/items";
        return $this->request('PATCH', $endpoint, ['json' => $data]);
    }

    /**
     * @param string $venueId
     * @param array $data
     * @return array|null
     * @throws GuzzleException
     */
    public function updateInventory(string $venueId, array $data): ?array
    {
        $endpoint = "/venues/$venueId/items/inventory";
        return $this->request('PATCH', $endpoint, ['json' => $data]);
    }
}
