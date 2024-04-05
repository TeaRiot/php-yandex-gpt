<?php

namespace TeaRiot\YandexGpt;

use TeaRiot\YandexGpt\Exception\ClientException;

/**
 * Handles sending HTTP requests.
 */
class Request
{
    const METHOD_GET = 'get';
    const METHOD_POST = 'post';

    /**
     * Sends an HTTP request to a specified URL with provided data and headers.
     *
     * @param string $url The URL to send the request to.
     * @param array $data The data to be sent with the request.
     * @param array $headers An array of headers to be sent with the request.
     * @return string The response as a string.
     * @throws ClientException If the request fails.
     */
    protected function send(string $url, array $data, array $headers): string
    {
        $method = $this->getMethodFromData($data);
        $ch = curl_init();

        if ($method === self::METHOD_GET) {
            $this->configureGetRequest($ch, $url, $data, $headers);
        } else {
            $this->configurePostRequest($ch, $url, $data, $headers);
        }

        $response = curl_exec($ch);

        if ($response === false) {
            throw new ClientException(curl_error($ch));
        }

        curl_close($ch);

        return $response;
    }

    /**
     * Determines the HTTP method from the data array.
     *
     * @param array $data The data array, potentially containing the method type.
     * @return string The determined HTTP method, defaulting to POST.
     */
    private function getMethodFromData(array &$data): string
    {
        if (isset($data['method']) && strtolower($data['method']) === self::METHOD_GET) {
            unset($data['method']);
            return self::METHOD_GET;
        }

        return self::METHOD_POST;
    }

    /**
     * Configures a GET request.
     *
     * @param resource $ch cURL handle.
     * @param string $url The URL for the request.
     * @param array $data The data array containing query parameters.
     * @param array $headers Request headers.
     */
    private function configureGetRequest($ch, string $url, array $data, array $headers)
    {
        if (!empty($data)) {
            $url .= '?' . http_build_query($data);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        $this->setCommonCurlOptions($ch, $headers);
    }

    /**
     * Configures a POST request.
     *
     * @param resource $ch cURL handle.
     * @param string $url The URL for the request.
     * @param array $data The data to be sent as JSON in the request body.
     * @param array $headers Request headers.
     */
    private function configurePostRequest($ch, string $url, array $data, array $headers)
    {
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        $this->setCommonCurlOptions($ch, $headers);
    }

    /**
     * Sets common cURL options for both GET and POST requests.
     *
     * @param resource $ch cURL handle.
     * @param array $headers Request headers.
     */
    private function setCommonCurlOptions($ch, array $headers)
    {
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
    }
}
