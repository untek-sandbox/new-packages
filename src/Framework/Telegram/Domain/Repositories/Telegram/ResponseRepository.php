<?php


namespace Untek\Framework\Telegram\Domain\Repositories\Telegram;

use Untek\Framework\Telegram\Domain\Entities\BotEntity;
use Untek\Framework\Telegram\Domain\Entities\ResponseEntity;
use Untek\Framework\Telegram\Domain\Helpers\HttpHelper;
use Untek\Framework\Telegram\Domain\Interfaces\Repositories\ResponseRepositoryInterface;

class ResponseRepository implements ResponseRepositoryInterface
{

    const URL = 'https://api.telegram.org';

    public function send(ResponseEntity $responseEntity, BotEntity $botEntity)
    {
        $data = $this->responseEntityToArray($responseEntity);
        $query = http_build_query($data);
        $token = $botEntity->getToken();
        $uri = "bot$token/sendMessage";
        $url = self::URL . "/$uri?$query";
        $json = HttpHelper::getHtml($url);
        $response = json_decode($json);
        if (empty($response->ok)) {
            $description = $response->description ? $response->description : 'Driver error!';
            throw new \Exception($description);
        }
    }

    private function responseEntityToArray(ResponseEntity $responseEntity): array
    {
        $data['text'] = $responseEntity->getText();
        $data['chat_id'] = $responseEntity->getChatId();
        $data['disable_notification'] = $responseEntity->getDisableNotification();
        $data['disable_web_page_preview'] = $responseEntity->getDisableWebPagePreview();
        $data['parse_mode'] = $responseEntity->getParseMode();
        $data['reply_markup'] = $responseEntity->getReplyMarkup();
        foreach ($data as $key => $value) {
            if (empty($value)) {
                unset($data[$key]);
            }
        }
        return $data;
    }
}