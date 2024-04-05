<?php

namespace TeaRiot\YandexGpt\Methods;

use TeaRiot\YandexGpt\Exception\ClientException;
use TeaRiot\YandexGpt\Limit;
use TeaRiot\YandexGpt\Message;
use TeaRiot\YandexGpt\Task;
use TeaRiot\YandexGpt\Url;


class Completion extends Task
{
    /**
     * The identifier of the model to be used for completion generation.
     * @link https://cloud.yandex.ru/ru/docs/yandexgpt/api-ref/v1/TextGeneration/completion
     */
    const MODEL_URI = 'modelUri';

    /**
     * Enables streaming of partially generated text.
     * @link https://cloud.yandex.ru/ru/docs/yandexgpt/api-ref/v1/TextGeneration/completion
     */
    const STREAM = 'stream';

    /**
     * Affects creativity and randomness of responses. Should be a double number between 0 (inclusive) and 1 (inclusive).
     * Lower values produce more straightforward responses, while higher values lead to increased creativity and randomness.
     * Default temperature: 0.6
     * @link https://cloud.yandex.ru/ru/docs/yandexgpt/api-ref/v1/TextGeneration/completion
     */
    const TEMPERATURE = 'temperature';

    /**
     * The limit on the number of tokens used for single completion generation.
     * Must be greater than zero. The maximum allowed parameter value may depend on the model used.
     * @link https://cloud.yandex.ru/ru/docs/yandexgpt/api-ref/v1/TextGeneration/completion
     */
    const MAX_TOKENS = 'maxTokens';

    /**
     * Textual content of the message.
     */
    const TEXT = 'text';

    /**
     * Configuration options for completion generation.
     */
    const COMPLETION_OPTIONS = 'completionOptions';

    /**
     * A list of messages representing the context for the completion model.
     */
    const MESSAGES = 'messages';

    /**
     * Identifier of the message sender. Supported roles:
     */
    const ROLE = 'role';

    /**
     * Special role used to define the behaviour of the completion model
     */
    const SYSTEM = 'system';

    /**
     * A role used by the model to generate responses
     */
    const ASSISTANT = 'assistant';

    /**
     * A role used by the user to describe requests to the model
     */
    const USER = 'user';

    private $url = Url::COMPLETION;

    public function __construct()
    {
        $this->setStream(false);
        $this->setTemperature(0.6); // Default temperature: 0.6
    }

    /**
     * @param array $textsData
     * @return $this
     */
    public function addText(array $textsData): self
    {
        if (count($textsData) > Limit::TEXT_MAX_COUNT)
            throw new ClientException(Message::LENGTH_ERROR);

        foreach ($textsData as $text) {
            if (mb_strlen($text['text']) > Limit::TEXT_LENGTH)
                throw new ClientException(Message::LENGTH_ERROR);

            $this->task[self::MESSAGES][] = [
                self::ROLE => $text[self::ROLE],
                self::TEXT => $text[self::TEXT],
            ];
        }

        return $this;
    }

    /**
     * @param float $temperature
     * @link https://cloud.yandex.ru/ru/docs/yandexgpt/api-ref/v1/TextGeneration/completion
     * @return Completion
     */
    public function setTemperature(float $temperature): Completion
    {
        $this->task[self::COMPLETION_OPTIONS][self::TEMPERATURE] = $temperature;
        return $this;
    }

    /**
     * @param bool $stream
     * @link https://cloud.yandex.ru/ru/docs/yandexgpt/api-ref/v1/TextGeneration/completion
     * @return Completion
     */
    public function setStream(bool $stream): Completion
    {
        $this->task[self::COMPLETION_OPTIONS][self::STREAM] = $stream;
        return $this;
    }

    /**
     * @param string $folderId
     * @param string $modelName
     * @link https://cloud.yandex.ru/ru/docs/yandexgpt/api-ref/v1/TextGeneration/completion
     * @return $this
     */
    public function setModelUri(string $folderId, string $modelName): Completion
    {
        $this->task[self::MODEL_URI] = "gpt://$folderId/$modelName";
        return $this;
    }

    /**
     * @param int $maxTokens
     * @return void
     * @link https://cloud.yandex.ru/ru/docs/yandexgpt/concepts/limits
     */
    public function setMaxTokens(int $maxTokens)
    {
        $this->task[self::COMPLETION_OPTIONS][self::MAX_TOKENS] = $maxTokens;
    }

    /**
     * @return string URL-адрес
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * Sets the operation mode to asynchronous.
     * In asynchronous mode, the request does not wait for the complete text generation by the model.
     * Instead, it initiates the generation process and immediately returns, allowing for the process to be completed in the background.
     * Use this mode for long-running text generation tasks where immediate response is not critical.
     *
     * @return Completion Returns the instance of this class for fluent interface, with the URL set for asynchronous operation.
     */
    public function isAsync(): Completion
    {
        $this->url = Url::COMPLETION_ASYNC;
        return $this;
    }


}
