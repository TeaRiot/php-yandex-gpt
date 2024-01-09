<?php

namespace TeaRiot\YandexGpt;

abstract class Task
{
    /**
     * @var string[]
     */
    public $headers = ['Content-Type: application/json'];

    /**
     * @var array
     */
    protected $task = [];

    /**
     * @param array $param
     */
    public function addParam(array $param)
    {
        $this->task += $param;
    }

    /**
     * @return string
     */
    abstract public function getUrl(): string;

    /**
     * @return string|null
     */
    public function getParam()
    {
        return ($this->task !== []) ? json_encode($this->task) : null;
    }
}
