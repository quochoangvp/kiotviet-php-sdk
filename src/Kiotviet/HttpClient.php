<?php
/**
 * Created by PhpStorm.
 * User: tuyenvv
 * Date: 2/11/19
 * Time: 6:13 PM
 */

namespace Kiotviet\Kiotviet;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class HttpClient
{
    protected $accessToken;
    protected $retailer;

    /**
     * HttpClient constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->accessToken = Authentication::$accessToken;
        $this->retailer = Authentication::$retailer;

        if (empty($this->accessToken) || empty($this->retailer)) {
            throw new \Exception('Missing access token, please check!');
        }
    }

    /**
     * @param $method
     * @param $url
     * @param $params
     * @param array $headers
     * @return mixed|\Psr\Http\Message\ResponseInterface|string
     * @throws \Exception
     */
    public function doRequest($method, $url, $params, $headers = [])
    {
        $client = new Client();

        $options = [];


        $options['headers'] = [
            'Retailer' => $this->retailer,
            'Authorization' => 'Bearer ' . $this->accessToken
        ];

        if (sizeof($headers) > 0) {
            $options['headers'] = array_merge($options['headers'], $headers);
        }

        if($method == 'GET'){
            $options['query'] = $params;
        }else{
            $options['form_params'] = $params;
        }

        try {
            $response = $client->request($method, $url, $options);
        } catch (GuzzleException $e) {
            return $this->responseError($e->getMessage(), 'Lỗi kết nối tới Kiotviet: ' . $e->getMessage());
        }

        $response = $response->getBody()->getContents();
        $response = json_decode($response, true);

        return $this->responseSuccess($response);
    }

    private function responseSuccess($data)
    {
        return [
            'status' => 'success',
            'data' => $data,
            'message' => 'Done!'
        ];
    }

    private function responseError($errors, $message)
    {
        return [
            'status' => 'error',
            'data' => null,
            'error' => $errors,
            'message' => $message
        ];
    }
}