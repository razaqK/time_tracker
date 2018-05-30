<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 5/25/18
 * Time: 7:21 AM
 */

namespace App\Traits;


trait ApiResponse
{
    public function getHeaders()
    {
        return [
            'Access-Control-Allow-Origin'      => '*'
        ];
    }

    /**
     * @param $data
     * @param int $statusCode
     * @param null $message
     * @param array $headers
     * @return mixed
     */
    public function sendSuccess($data, $statusCode = 200, $message = null, $headers = [])
    {
        return $this->processResponse('success', $message, $data, null, $statusCode, $headers);
    }

    /**
     * @param $message
     * @param $code
     * @param int $statusCode
     * @param null $data
     * @param array $headers
     * @return mixed
     */
    public function sendError($message, $code, $statusCode = 500, $data = null, $headers = [])
    {
        return $this->processResponse('error', $message, $data, $code, $statusCode, $headers);
    }

    /**
     * @param $status
     * @param $message
     * @param $data
     * @param $code
     * @param int $statusCode
     * @param $headers
     * @return mixed
     */
    protected function processResponse($status, $message, $data, $code, int $statusCode, $headers)
    {
        $response = [
            'status' => $status
        ];

        if (!is_null($message)) {
            $response['message'] = $message;
        }

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        if (!is_null($code)) {
            $response['code'] = $code;
        }

        $headers = array_merge($headers, $this->getHeaders());

        return $this->json($response, $statusCode, $headers);
    }
}