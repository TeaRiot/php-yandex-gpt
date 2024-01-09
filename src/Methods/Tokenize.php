<?php

namespace TeaRiot\YandexGpt\Methods;

use TeaRiot\YandexGpt\Task;
use TeaRiot\YandexGpt\Url;

class Tokenize extends Task
{
    /**
     * The identifier of the model to be used for tokenization.
     */
    const MODEL_URI = 'modelUri';

    /**
     * Text to be tokenized.
     */
    const TOKENIZE = 'text';

    public function __construct(string $message = null)
    {
        if (!is_null($message))
            $this->task[self::TOKENIZE] = $message;

    }

    public function setModelUri(string $folderId, string $modelName)
    {
        $this->task[self::MODEL_URI] = "gpt://$folderId/$modelName";
    }

    /**
     * @return string URL-адрес
     */
    public function getUrl(): string
    {
        return Url::TOKENIZER;
    }

}