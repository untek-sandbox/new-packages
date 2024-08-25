<?php

namespace Untek\Framework\Telegram\Domain\Libs\Loaders\BundleLoaders;

use Untek\Core\Bundle\Base\BaseLoader;
use Untek\Component\Arr\Helpers\ExtArrayHelper;

class TelegramRoutesLoader extends BaseLoader
{

    public function loadAll(array $bundles): void
    {
        $config = [];
        foreach ($bundles as $bundle) {
            $containerConfigList = $this->load($bundle);
            if ($containerConfigList) {
                $config = ExtArrayHelper::merge($config, $containerConfigList);
            }
        }
        $this->getConfigManager()->set('telegramRoutes', $config);
    }
}
