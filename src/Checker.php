<?php

namespace Attla\Disposable;

class Checker
{
    /**
     * @var string
     */
    private $decisionRateLimit;

    /**
     * @var string
     */
    private $decisionNoMx;

    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    /**
     * @var string
     */
    private $key;

    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => 'https://www.validator.pizza',
        ]);

        $config = config();

        $this->decisionRateLimit = $config->get('disposable.decision_rate_limit');
        $this->decisionNoMx = $config->get('disposable.decision_no_mx');
        $this->key = $config->get('disposable.key');
    }

    /**
     * Check domain
     *
     * @param string $domain
     * @return bool
     */
    public function allowedDomain(string $domain): bool
    {
        $response = $this->query($domain);

        // Rate limit exceeded
        if (429 == $response->status) {
            return 'allow' == $this->decisionRateLimit ? true : false;
        }

        if (200 != $response->status) {
            return false;
        }

        return $this->decideIsValid($response);
    }

    /**
     * Check email address
     *
     * @param string $email
     * @return bool
     */
    public function allowedEmail(string $email): bool
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        [$local, $domain] = explode('@', $email, 2);

        return $this->allowedDomain($domain);
    }

    /**
     * Query the API
     *
     * @param string $domain
     *
     * @throws \GuzzleHttp\Exception\ClientException
     * @return \stdClass API response data
     */
    private function query(string $domain): \stdClass
    {
        $uri = '/domain/' . strtolower($domain);

        if ($this->key) {
            $uri .= '?key=' . $this->key;
        }

        $request = new \GuzzleHttp\Psr7\Request('GET', $uri, [
            'Accept' => 'application/json',
        ]);

        try {
            $response = $this->client->send($request);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            return (object) [
                'status' => $e->getResponse()->getStatusCode(),
            ];
        }

        $data = json_decode($response->getBody());

        return (object) [
            'status' => 200,
            'domain' => $data->domain,
            'mx' => optional($data)->mx ?? false,
            'disposable' => optional($data)->disposable ?? false,
        ];
    }

    /**
     * Decide wether the given data represents a valid domain
     *
     * @param \stdClass $data
     * @return bool
     */
    private function decideIsValid(\stdClass $data): bool
    {
        if ('deny' == $this->decisionNoMx && true !== optional($data)->mx) {
            return false;
        }

        return false === optional($data)->disposable;
    }
}
