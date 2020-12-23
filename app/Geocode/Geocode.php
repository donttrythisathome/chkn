<?php


namespace App\Geocode;


use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use UnexpectedValueException;

class Geocode
{
    /** @var string */
    protected $apiKey;

    /** @var string */
    protected $format = 'json';

    /** @var string */
    protected $baseUrl;

    /** @var Client */
    protected $transport;

    /** @var int */
    protected $posDecimals = 8;

    /**
     * @param string $apiKey
     * @param string $baseUrl
     * @param Client $transport
     */
    public function __construct(string $apiKey, string $baseUrl, Client $transport)
    {
        $this->apiKey = $apiKey;
        $this->baseUrl = $baseUrl;
        $this->transport = $transport;
    }

    public function getLocation(string $address): array
    {
        $raw = $this->getRawLocation($address);
        $location = explode(' ', $raw);
        if (count($location) != 2) {
            throw  new UnexpectedValueException("invalid raw location [$raw]");
        }
        return array_map(function ($item) {
            return $this->formatPos($item);
        }, $location);
    }

    protected function getRawLocation(string $address): string
    {
        $arr = $this->geocode($address);
        $location = "";
        array_walk_recursive($arr, function ($item, $key) use (&$location) {
            if ($key === 'pos') $location = $item;
        });

        return $location;
    }


    public function geocode(string $address): array
    {
        $res = $this->transport->get($this->baseUrl, [
            RequestOptions::QUERY => [
                'format' => $this->format,
                'apikey' => $this->apiKey,
                'geocode' => $address
            ]
        ]);

        $arr = json_decode((string)$res->getBody(), true);
        if (json_last_error() != 0) {
            throw new UnexpectedValueException();
        }

        return $arr;
    }

    protected function formatPos(string $pos): string
    {
        return number_format($pos, $this->posDecimals);
    }
}
