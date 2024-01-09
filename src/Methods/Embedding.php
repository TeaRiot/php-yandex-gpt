<?php

namespace TeaRiot\YandexGpt\Methods;

use TeaRiot\YandexGpt\Task;
use TeaRiot\YandexGpt\Url;

class Embedding extends Task
{
    /**
     * The identifier of the model to be used for obtaining text embeddings.
     */
    const MODEL_URI = 'modelUri';

    /**
     * The input text for which the embedding is requested.
     */
    const TOKENIZE = 'text';

    public function __construct(string $message = null)
    {
        if (!is_null($message))
            $this->task[self::TOKENIZE] = $message;

    }

    public function setModelUri(string $folderId, string $modelName)
    {
        $this->task[self::MODEL_URI] = "emb://$folderId/$modelName";
    }

    /**
     * @return string URL-адрес
     */
    public function getUrl(): string
    {
        return Url::EMBEDDING;
    }

}