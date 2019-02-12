<?php
/**
 * Created by PhpStorm.
 * User: tuyenvv
 * Date: 2/11/19
 * Time: 2:31 PM
 */

namespace Kiotviet;


use Kiotviet\Kiotviet\Authentication;
use Kiotviet\Kiotviet\HttpClient;

/**
 * Class Kiotviet
 * @package Kiotviet
 */
class Kiotviet
{
    /**
     *
     */
    const VERSION = '1.0.0';

    /**
     * @var
     */
    protected $accessToken;

    /**
     * @var
     */
    protected $retailer;

    /**
     * @var KiotvietConfig|null
     */
    protected $config;

    /**
     * @var HttpClient
     */
    protected $client;


    /**
     * Kiotviet constructor.
     * @param KiotvietConfig|null $config
     * @throws \Exception
     */
    public function __construct(KiotvietConfig $config = null)
    {
        $this->config = $config;
        
        list($clientId, $clientSecret, $retailer) = $this->config->getConfig();
        Authentication::getAccessToken($clientId, $clientSecret, $retailer);

        //  Get access token before create new Http client
        $this->client = new HttpClient();
    }

    /**
     * @return mixed
     */
    public function getAccessToken()
    {
        return Authentication::$accessToken;
    }

    /**
     * @return mixed
     */
    public function getRetailer()
    {
        return Authentication::$retailer;
    }

    /**
     * @param $url
     * @param array $params
     * @return mixed|\Psr\Http\Message\ResponseInterface|string
     * @throws \Exception
     */
    public function get($url, array $params)
    {
        return $this->client->doRequest('GET', $url, $params);
    }

    /**
     * @param $url
     * @param array $params
     * @return mixed|\Psr\Http\Message\ResponseInterface|string
     * @throws \Exception
     */
    public function post($url, array $params)
    {
        return $this->client->doRequest('POST', $url, $params);
    }

    /**
     * @param $url
     * @param array $params
     * @return mixed|\Psr\Http\Message\ResponseInterface|string
     * @throws \Exception
     */
    public function put($url, array $params)
    {
        return $this->client->doRequest('PUT', $url, $params);
    }

    /**
     * @param $url
     * @param array $params
     * @return mixed|\Psr\Http\Message\ResponseInterface|string
     * @throws \Exception
     */
    public function delete($url, array $params)
    {
        return $this->client->doRequest('DELETE', $url, $params);
    }

}