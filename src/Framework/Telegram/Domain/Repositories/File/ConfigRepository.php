<?php


namespace Untek\Framework\Telegram\Domain\Repositories\File;

use Untek\Component\Arr\Helpers\ExtArrayHelper;
use Untek\Lib\Components\Store\StoreFile;

class ConfigRepository
{

    private $longpullTimeout = 30;
    private $token = 30;
    
    public function __construct(?string $botToken)
    {
        $this->token = $botToken;
    }

    public function getBotToken() {
        return $this->token;
    }

    public function getLongpullTimeout() {
        return $this->longpullTimeout;
        
//        return $this->getBotConfig('timeout', 30);
    }
    
    /*private function getBotConfig(string $name, $default = null) {
        $mainConfig = include __DIR__ . '/../../../../../../../config/main.php';
        $botConfig = $mainConfig['telegram']['bot'];
        return ExtArrayHelper::getValue($botConfig, $name, $default);
    }*/
}
