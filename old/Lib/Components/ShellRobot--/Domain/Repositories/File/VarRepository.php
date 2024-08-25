<?php

namespace Untek\Lib\Components\ShellRobot\Domain\Repositories\File;

use Illuminate\Support\Arr;
use Untek\Component\Arr\Helpers\ArrayPathHelper;
use Untek\Component\Arr\Helpers\ExtArrayHelper;
use Untek\Component\Text\Helpers\TemplateHelper;
use Untek\Lib\Components\ShellRobot\Domain\Interfaces\Repositories\VarRepositoryInterface;
use Yiisoft\Arrays\ArrayHelper;

class VarRepository implements VarRepositoryInterface
{

    private $vars;

    public function __construct(array $vars)
    {
        $this->setVars($vars);
    }

    public function process(string $value): string
    {
        return $this->render($value, $this->vars);
    }

    public function processList(array $list): array
    {
        $callback = [$this, 'process'];
        $list = array_map($callback, $list);
        return $list;
    }

    public function set(string $key, $value): void
    {
        Arr::set($this->vars, $key, $value);
        $this->initVars();
    }

    public function setList(array $list): void
    {
        foreach ($list as $key => $value) {
            Arr::set($this->vars, $key, $value);
        }
        $this->initVars();
    }

    public function get(string $key, $default = null)
    {
        $value = ArrayPathHelper::getValue($this->vars, $key, $default);
        $value = $this->prepareItem($value);
        return $value;
    }

    private function prepareItem($value)
    {
        if (is_callable($value)) {
            $value = call_user_func($value);
        }
        return $value;
    }

    private function prepareItems($vars)
    {
        foreach ($vars as $varKey => $varValue) {
            $vars[$varKey] = $this->prepareItem($varValue);
        }
        return $vars;
    }

    private function render($value, $vars)
    {
        $vars = $this->prepareItems($vars);
        $value = $this->prepareItem($value);
        return TemplateHelper::render($value, $vars, '{{', '}}');
    }

    private function setVars(array $vars): void
    {
        $this->vars = $vars;
        $this->initVars();
    }

    private function initVars()
    {
        $this->vars = $this->processVars($this->vars);
    }

    private function processVars($vars)
    {
        do {
            $oldVars = $vars;
            foreach ($vars as $index => $var) {
                $vars[$index] = $this->render($var, $vars);
            }
        } while ($oldVars !== $vars);
        return $vars;
    }
}
