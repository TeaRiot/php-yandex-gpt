<?php

namespace TeaRiot\YandexGpt;

class Url
{
    // Sync methods
    /**
     * @link https://cloud.yandex.ru/docs/iam/operations/iam-token/create
     */
    const TOKENS = 'https://iam.api.cloud.yandex.net/iam/v1/tokens';

    /**
     * Service for obtaining embeddings from input data.
     * @link https://cloud.yandex.ru/ru/docs/yandexgpt/api-ref/v1/Embeddings/
     */
    const EMBEDDING = 'https://llm.api.cloud.yandex.net/foundationModels/v1/textEmbedding';

    /**
     * Service for text generation.
     * @link https://cloud.yandex.ru/ru/docs/yandexgpt/api-ref/v1/TextGeneration/
     */
    const COMPLETION = 'https://llm.api.cloud.yandex.net/foundationModels/v1/completion';

    /**
     * Service for tokenizing input content.
     * @link https://cloud.yandex.ru/ru/docs/yandexgpt/api-ref/v1/Tokenizer/
     */
    const TOKENIZER = 'https://llm.api.cloud.yandex.net/foundationModels/v1/tokenizeCompletion';

    const OPERATION = 'https://operation.api.cloud.yandex.net/operations';

    /**
     * Service for text generation.
     * @link https://cloud.yandex.ru/ru/docs/yandexgpt/api-ref/v1/TextGeneration/
     */
    const COMPLETION_ASYNC = 'https://llm.api.cloud.yandex.net/foundationModels/v1/completionAsync';

}
