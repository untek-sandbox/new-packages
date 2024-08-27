<?php

namespace Untek\Component\Web\Widget\Base;

use Untek\Component\Text\Helpers\TemplateHelper;
use Untek\Component\Web\Widget\Interfaces\WidgetInterface;
use Untek\Core\Instance\Helpers\ClassHelper;

abstract class BaseWidget implements WidgetInterface
{

    abstract public function render(): string;

    public static function widget(array $options = []): string
    {
        $instance = ClassHelper::createObject(static::class, $options);
        return $instance->render();
    }

    protected function renderTemplate(string $templateCode, array $params)
    {
        return TemplateHelper::render($templateCode, $params);
    }
}