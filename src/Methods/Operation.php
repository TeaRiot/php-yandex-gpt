<?php

namespace TeaRiot\YandexGpt\Methods;

use TeaRiot\YandexGpt\Request;
use TeaRiot\YandexGpt\Task;
use TeaRiot\YandexGpt\Url;

/**
 * Represents an operation in the Yandex Cloud API.
 * This class handles creating URLs for operations and setting up the necessary parameters.
 */
class Operation extends Task
{
    /**
     * @var string The URL for the operation.
     */
    private $url;

    /**
     * Operation constructor.
     */
    public function __construct()
    {
        // Constructor can be extended for initialization if needed.
    }

    /**
     * Sets up a GET request for a specific operation by its ID.
     *
     * @param string $id The ID of the operation.
     * @return Operation The instance of this class for fluent interface.
     */
    public function get(string $id): Operation
    {
        $this->url = Url::OPERATION . "/$id";
        $this->addParam(['method' => Request::METHOD_GET]);
        return $this;
    }

    /**
     * Sets up a request for an operation and indicates that it should wait for completion.
     * This will check the status of the operation until it is 'done'.
     *
     * @param string $id The ID of the operation.
     * @return Operation The instance of this class for fluent interface.
     */
    public function waitAndGet(string $id): Operation
    {
        $this->url = Url::OPERATION . "/$id";
        $this->addParam(
            [
                'markerKey' => 'done', // The marker key that indicates the operation status.
                'method' => Request::METHOD_GET,
            ]
        );
        return $this;
    }

    /**
     * Sets a custom timeout for the operation.
     *
     * @param int $timeout The timeout in seconds.
     * @return Operation The instance of this class for fluent interface.
     */
    public function setTimeOut(int $timeout): Operation
    {
        $this->addParam(['timeout' => $timeout]);
        return $this;
    }

    /**
     * Retrieves the URL of the operation.
     *
     * @return string The URL of the operation.
     */
    public function getUrl(): string
    {
        return $this->url;
    }

}
