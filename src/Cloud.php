<?php

namespace TeaRiot\YandexGpt;

class Cloud extends Request
{
    /**
     * Parameter name "OAuth token"
     * @link https://cloud.yandex.com/docs/iam/operations/iam-token/create
     */
    const YANDEX_PASSPORT_OAUTH_TOKEN = 'yandexPassportOauthToken';

    /**
     * Parameter name "Folder ID"
     * @link https://cloud.yandex.com/docs/yandexgpt/api-ref/v1/
     */
    const FOLDER_ID = 'folderId';

    /**
     * Parameter name "Bearer" in the "Authorization" header
     * @link https://cloud.yandex.com/docs/iam/concepts/authorization/iam-token
     */
    const BEARER = 'Bearer';

    /**
     * Parameter name "Api-Key" in the "Authorization" header
     * @link https://cloud.yandex.com/docs/iam/concepts/authorization/api-key
     */
    const API_KEY = 'Api-Key';

    /**
     * @var array Web request headers
     */
    private $headers = [];

    /**
     * @var array Task/request parameters
     */
    private $task = [];


    /**
     * Cloud constructor.
     * @param string|null $token OAuth-token / IAM-token
     * @param string|null $folderId x-folder-id
     */
    public function __construct(string $token = null,
                                string $folderId = null
    )
    {
        if (!is_null($token)) {
            if (!is_null($folderId)) {
                if (strlen($folderId) > Limit::FOLDER_ID_LENGTH){
                    throw new Exception\ClientException(Message::LENGTH_ERROR);
                }

                $this->authenticateWithFolderId($token, $folderId);
            } else
                $this->authenticateWithToken($token);
        }
    }

    /**
     * @param string $token
     * @param string $folderId
     * @return void
     */
    private function authenticateWithFolderId(string $token, string $folderId)
    {
        $this->addAuthHeader(self::BEARER,
            $this->getIamToken($token)
        )->task[self::FOLDER_ID] = $folderId;
    }

    /**
     * @param string $token
     * @return void
     */
    private function authenticateWithToken(string $token)
    {
        $this->addAuthHeader(self::BEARER, $token);
    }

    /**
     * @param string $apiKey API-key
     * @return static
     */
    public static function createApi(string $apiKey): self
    {
        return (new self)->addAuthHeader(self::API_KEY, $apiKey);
    }

    /**
     * @param string $oAuthToken OAuth-token
     * @return string IAM-token
     */
    private function getIamToken(string $oAuthToken): string
    {
        $response = $this->send(Url::TOKENS,
            [self::YANDEX_PASSPORT_OAUTH_TOKEN => $oAuthToken],
            ['Content-Type: application/json']);

        return (string) json_decode($response)->iamToken;
    }

    /**
     * @param string $authType
     * @param string $reason IAM-token / API-key
     * @return $this
     */
    private function addAuthHeader(string $authType,
                                   string $reason): self
    {
        $this->headers[] = sprintf("Authorization: %s %s",
            $authType,
            $reason);

        return $this;
    }

    /**
     * @param Task $task
     * @return string
     */
    public function request(Task $task): string
    {
        $task->addParam($this->task);

        $params = $task->getParam();
        $result = $this->send($task->getUrl(), $params, array_merge($this->headers, $task->headers));

        if (isset($params['markerKey'])) {
            return $this->waitForCompletion($result, $task);
        }

        return $result;
    }

    /**
     * Waits for the completion of a task and returns the result in a JSON-encoded format.
     * Keeps sending requests until 'done' status is true or until the timeout (2 minutes) is reached.
     * Returns a JSON-encoded empty array if the timeout is reached without completion.
     *
     * @param string $result The initial result of the task.
     * @param Task $task The task being processed.
     * @return string JSON-encoded final result of the task processing.
     */
    private function waitForCompletion(string $result, Task $task): string
    {
        $params = $task->getParam();
        $startTime = time();

        $timeout = $params['timeout'] ?? 120;
        do {
            $response = json_decode($result, true);
            if (isset($response['done']) && $response['done'] === true) {
                return json_encode($response['response']);
            }
            sleep(2);
            $result = $this->send($task->getUrl(), $params, array_merge($this->headers, $task->headers));
        } while (time() - $startTime < $timeout);

        return json_encode([]);
    }
}
