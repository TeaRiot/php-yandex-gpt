<?php

namespace TeaRiot\YandexGpt;

use TeaRiot\YandexGpt\Exception\ClientException;

class Request
{
    /**
     * @param string $url
     * @param string $data
     * @param array $headers
     * @return string
     */
    protected function send(string $url, string $data, array $headers): string
    {
        $ch = curl_init($url);

        $this->setCurlOptions($ch, $data, $headers);

        $response = curl_exec($ch);

        if ($response === false) {
            throw new ClientException(curl_error($ch));
        }

        curl_close($ch);

        return $response;
    }

    /**
     * Set cURL options based on provided data and headers
     *
     * @param resource $ch
     * @param string|null $data
     * @param array $headers
     */
    private function setCurlOptions($ch, string $data, array $headers)
    {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        if (!is_null($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
    }
}
