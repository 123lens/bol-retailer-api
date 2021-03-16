<?php
namespace Budgetlens\BolRetailerApi\Middleware;


use Budgetlens\BolRetailerApi\Contracts\Config;
use Budgetlens\BolRetailerApi\Exceptions\AuthenticationException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Client as HttpClient;

final class RefreshToken
{
    private $config;
    private $token;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function __invoke(callable $next)
    {
        return function (RequestInterface $request, array $options = []) use ($next) {
            $request = $this->applyToken($request);
            return $next($request, $options);
        };
    }

    /**
     * Check for valid access token
     * @return bool
     */
    private function hasValidToken(): bool
    {
        $this->loadTokenFromCache();

        if (!is_array($this->token)) {
            return false;
        }

        if (!isset($this->token['expires_at']) ||
            !isset($this->token['access_token'])
        ) {
            return false;
        }

        return $this->token['expires_at'] > time();
    }

    /**
     * Load token from cache
     */
    private function loadTokenFromCache(): void
    {
        if (!is_array($this->token) &&
            $this->config->cacheToken()
        ) {
            // only load cache if no token is present.
            $tokenFile = $this->getTokenFile();
            if (file_exists($tokenFile)) {
                $this->token = unserialize(file_get_contents($tokenFile));
            }
        }
    }

    /**
     * Set Token Cache
     */
    private function setTokenCache(): void
    {
        if ($this->config->cacheToken()) {
            $tokenFile = $this->getTokenFile();
            @file_put_contents($tokenFile, serialize($this->token));
        }
    }

    /**
     * Get Token File
     * @return string
     */
    private function getTokenFile(): string
    {
        return dirname(__FILE__) . '/../Cache/token.cache';
    }
    /**
     * Apply bearer token to request
     * @param RequestInterface $request
     * @return RequestInterface
     * @throws AuthenticationException
     */
    protected function applyToken(RequestInterface $request)
    {
        if (!$this->hasValidToken()) {
            $this->acquireAccessToken();
        }

        return $request->withHeader('Authorization', 'Bearer ' . (string)$this->getToken());
    }

    /**
     * Return access token
     * @return string
     */
    private function getToken(): string
    {
        return (string)$this->token['access_token'];
    }

    /**
     * Acquire Access Token
     * @throws AuthenticationException
     */
    private function acquireAccessToken()
    {
        $headers = [
            'Accept' => 'application/json'
        ];
        try {
            $client = new HttpClient();
            $response = $client->request('POST', 'https://login.bol.com/token?grant_type=client_credentials', [
                'headers' => $headers,
                'auth' => [$this->config->getClientId(), $this->config->getClientSecret()]
            ]);
        } catch (GuzzleException $e) {
            if ($e instanceof RequestException) {
                $response = json_decode((string)$e->getResponse()->getBody(), true);
                throw new AuthenticationException($response['error_description'] ?? null);
            }

            throw new AuthenticationException($e->getMessage());
        }

        $token = json_decode((string)$response->getBody()->getContents(), true);
        if (!is_array($token) ||
            empty($token['access_token']) ||
            empty($token['expires_in'])
        ) {
            throw new AuthenticationException('Could not retrieve valid token from Bol API');
        }

        $token['expires_at'] = time() + $token['expires_in'] ?? 0;

        $this->token = $token;
        $this->setTokenCache();
    }
}
