<?php

namespace Untek\Framework\Telegram\Infrastructure;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\TransferException;
use RuntimeException;
use Untek\Core\Enum\Helpers\EnumHelper;
use Untek\Core\FileSystem\Helpers\FileStorageHelper;
use Untek\Core\Instance\Helpers\MappingHelper;
use Untek\Framework\Telegram\Application\Services\TelegramBotInterface;
use Untek\Framework\Telegram\Domain\Dto\ForwardMessageResult;
use Untek\Framework\Telegram\Domain\Dto\Photo;
use Untek\Framework\Telegram\Domain\Dto\SendDocumentResult;
use Untek\Framework\Telegram\Domain\Dto\SendMessageResult;
use Untek\Framework\Telegram\Domain\Dto\SendPhotoResult;
use Untek\Framework\Telegram\Domain\Enums\ParseModeEnum;
use Untek\Framework\Telegram\Infrastructure\Normalizer\EditMessageResultNormalizer;
use Untek\Framework\Telegram\Infrastructure\Normalizer\ForwardMessageResultNormalizer;
use Untek\Framework\Telegram\Infrastructure\Normalizer\SendDocumentResultNormalizer;
use Untek\Framework\Telegram\Infrastructure\Normalizer\SendMessageResultNormalizer;
use Untek\Framework\Telegram\Infrastructure\Normalizer\SendPhotoResultNormalizer;

class TelegramBot implements TelegramBotInterface
{

    public function __construct(private $botToken)
    {
    }

    /**
     * @param int $chatId
     * @param string $text
     * @param string $parseMode
     * @throws RuntimeException
     */
    public function sendMessage(int $chatId, string $text, string $parseMode = ''): SendMessageResult
    {
        EnumHelper::validate(ParseModeEnum::class, $parseMode);
        $requestData = [
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => $parseMode,
        ];
        $response = $this->sendRequest('sendMessage', $requestData);
        return (new SendMessageResultNormalizer())->denormalize($response, SendMessageResult::class);
    }

    public function sendDocument(int $chatId, string $file, string $caption = null, string $parseMode = ''): SendDocumentResult
    {
        EnumHelper::validate(ParseModeEnum::class, $parseMode);
        $options = [
            'multipart' => $this->toMultiPart([
                'chat_id'=> $chatId,
                'caption' => $caption,
                'document'=> fopen($file, 'r'),
                'parse_mode' => $parseMode,
            ])
        ];
        $response = $this->sendRequest('sendDocument', [], $options);
        return (new SendDocumentResultNormalizer())->denormalize($response, SendDocumentResult::class);
    }

    public function sendPhoto(int $chatId, string $file, string $caption = null, string $parseMode = ''): SendPhotoResult
    {
        EnumHelper::validate(ParseModeEnum::class, $parseMode);
        $options = [
            'multipart' => $this->toMultiPart([
                'chat_id'=> $chatId,
                'caption' => $caption,
                'photo'=> fopen($file, 'r'),
                'parse_mode' => $parseMode,
            ])
        ];
        $response = $this->sendRequest('sendPhoto', [], $options);
        return (new SendPhotoResultNormalizer())->denormalize($response, SendPhotoResult::class);
    }

    public function editMessage(int $chatId, int $messageId, string $text, string $parseMode = ''): SendMessageResult
    {
        EnumHelper::validate(ParseModeEnum::class, $parseMode);
        $requestData = [
            'chat_id' => $chatId,
            'message_id' => $messageId,
            'text' => $text,
            'parse_mode' => $parseMode,
        ];
        $response = $this->sendRequest('editMessageText', $requestData);
        return (new EditMessageResultNormalizer())->denormalize($response, SendMessageResult::class);
    }

    public function forwardMessage(int $fromChatId, int $chatId, int $messageId): ForwardMessageResult
    {
        $requestData = [
            'from_chat_id' => $fromChatId,
            'chat_id' => $chatId,
            'message_id' => $messageId,
        ];
        $response = $this->sendRequest('forwardMessage', $requestData);
        return (new ForwardMessageResultNormalizer())->denormalize($response, ForwardMessageResult::class);
    }

    private function sendRequest(string $path, array $requestData, array $options = [], string $method = 'POST'): array {
        $url = $this->generateUrl($path, $requestData);
        $client = new Client();
        try {
            $response = $client->request($method, $url, $options);
            $result = json_decode($response->getBody()->getContents(), true);
            return $result['result'];
        } catch (TransferException | GuzzleException $exception) {
            throw new RuntimeException($exception->getMessage());
        }
    }

    private function generateUrl(string $path, array $requestData): string {
        $requestQuery = http_build_query($requestData);
        $url = "https://api.telegram.org/bot{$this->botToken}/{$path}?{$requestQuery}";
        return $url;
    }

    private function toMultiPart(array $arr): array {
        $result = [];
        array_walk($arr, function($value, $key) use(&$result) {
            $result[] = ['name' => $key, 'contents' => $value];
        });
        return $result;
    }
}
