# PHP Yandex GPT Library

This PHP library provides a convenient interface to interact with Yandex GPT (Generative Pre-trained Transformer) API for text generation, tokenization, and obtaining embeddings.

## Installation

To install the library via Composer, use the following command:

```bash
composer require teariot/php-yandex-gpt
```

## Usage

Ensure you have obtained the necessary OAuth token and folder ID from Yandex GPT.

### Text Completion

To generate text completions, use the `complete` method:

```php
<?php

const OAuthToken = 'YOUR_OAUTH_TOKEN';
const folder_id = 'YOUR_FOLDER_ID';

public static function complete(string $message): array
{
    $cloud = new Cloud(self::OAuthToken, self::folder_id);
    $completion = new Completion();
    
    $completion->setModelUri(self::folder_id, 'yandexgpt-lite/latest')
            ->addText([
                [
                    'role' => $completion::USER,
                    'text' => $message,
                ]
            ]);

    $result = $cloud->request($completion);
    return json_decode($result, true);
}
?>
```

## Enhanced Usage of `complete` Method

This variation showcases an extended use case of the `complete` method by incorporating system messages along with user messages.

```php
<?php

const OAuthToken = 'YOUR_OAUTH_TOKEN';
const folder_id = 'YOUR_FOLDER_ID';

public static function complete(string $systemMessage, string $userMessage): array
{
    $cloud = new Cloud(self::OAuthToken, self::folder_id);
    $completion = new Completion();
    
    $completion->setModelUri(self::folder_id, 'yandexgpt-lite/latest')
            ->addText([
                [
                    'role' => $completion::SYSTEM,
                    'text' => $systemMessage,
                ],
                [
                    'role' => $completion::USER,
                    'text' => $message,
                ],
            ]);

    $result = $cloud->request($completion);
    return json_decode($result, true);
}
?>
```

### Tokenization

For tokenizing text, utilize the `tokenize` method:

```php
<?php

const OAuthToken = 'YOUR_OAUTH_TOKEN';
const folder_id = 'YOUR_FOLDER_ID';

public static function tokenize(string $message): array
{
    $cloud = new Cloud(self::OAuthToken, self::folder_id);
    $tokenize = new Tokenize($message);
    $tokenize->setModelUri(self::folder_id, 'yandexgpt/latest');
    
    $result = $cloud->request($tokenize);
    return json_decode($result, true);
}
?>
```

### Obtaining Embeddings

To obtain embeddings from text data, use the `embedding` method:

```php
<?php

const OAuthToken = 'YOUR_OAUTH_TOKEN';
const folder_id = 'YOUR_FOLDER_ID';

public static function embedding(string $message): array
{
    $cloud = new Cloud(self::OAuthToken, self::folder_id);
    $embedding = new Embedding($message);
    $embedding->setModelUri(self::folder_id, 'text-search-query/latest');
    
    $result = $cloud->request($embedding);
    return json_decode($result, true);
}
?>
```

Remember to replace `'YOUR_OAUTH_TOKEN'` and `'YOUR_FOLDER_ID'` with your actual credentials obtained from Yandex GPT.

For detailed information on available parameters and configurations, please refer to the library documentation or Yandex GPT API documentation.

## License

This library is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
