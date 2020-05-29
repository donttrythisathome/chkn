<?php

namespace App\Facilicom;

use GuzzleHttp\Client as Guzzle;
use GuzzleHttp\RequestOptions;

class Client
{
    const CHECKIN_ACTION = 4;
    const CHECKOUT_ACTION = 5;

    /** @var Guzzle */
    protected $transport;

    /** @var  string */
    protected $baseUrl;

    /** @var string */
    protected $login;

    /** @var string */
    protected $password;

    /**
     * Client constructor.
     * @param Guzzle $guzzle
     * @param string $baseUrl
     * @param string $login
     * @param string $password
     */
    public function __construct(Guzzle $guzzle, string $baseUrl, string $login, string $password)
    {
        $this->transport = $guzzle;
        $this->baseUrl = $baseUrl;
        $this->login = $login;
        $this->password = $password;
    }

    /**
     * @return array
     */
    public function logon(): array
    {
        $response = $this->transport->post($this->baseUrl . '/Logon', [
            RequestOptions::BODY => json_encode([
                'email' => $this->login,
                'password' => $this->password
            ]),
            RequestOptions::HEADERS => [
                'Content-Type' => 'application/json; charset=UTF-8',
                'User-Agent' => 'okhttp/3.11.0',
                'Accept-Encoding' => 'gzip'
            ]
        ]);

        return json_decode((string)$response->getBody(), true);
    }

    /**
     * @param string $token
     * @param int $accountId
     * @param int $actionType
     * @param float $lat
     * @param float $lng
     */
    public function setLocation(string $token, int $accountId, int $actionType, float $lat, float $lng)
    {
        $this->transport->post($this->baseUrl . '/Location', [
            RequestOptions::BODY => json_encode([
                'Accuracy' => 0,
                'ActionType' => $actionType,
                'DirectumID' => $accountId,
                'Latitude' => $lat,
                'Longitude' => $lng,
                'Offset' => 0
            ]),
            RequestOptions::HEADERS => [
                'Content-Type' => 'application/json; charset=UTF-8',
                'User-Agent' => 'okhttp/3.11.0',
                'Accept-Encoding' => 'gzip',
                'Authorization' => $token
            ]
        ]);
    }

    public function getAccounts(string $token)
    {
        $response = $this->transport->get($this->baseUrl . '/Accounts', [
            RequestOptions::HEADERS => [
                'Content-Type' => 'application/json; charset=UTF-8',
                'User-Agent' => 'okhttp/3.11.0',
                'Accept-Encoding' => 'gzip',
                'Authorization' => $token
            ]
        ]);

        return json_decode((string)$response->getBody(), true);
    }

    public function getCheckin(string $token)
    {
        $response = $this->transport->get($this->baseUrl . '/location/GetCheckIn', [
            RequestOptions::HEADERS => [
                'Content-Type' => 'application/json; charset=UTF-8',
                'User-Agent' => 'okhttp/3.11.0',
                'Accept-Encoding' => 'gzip',
                'Authorization' => $token
            ]
        ]);

        return json_decode((string)$response->getBody(), true);
    }

}
