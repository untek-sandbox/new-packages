<?php

namespace Untek\Utility\CodeGenerator\CodeGenerator\Infrastructure\Generator;

use Untek\Component\FormatAdapter\Store;
use Untek\Component\Package\Helpers\ComposerHelper;
use Untek\Core\Instance\Helpers\ClassHelper;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\FileResult;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Dto\GenerateResultCollection;
use Untek\Utility\CodeGenerator\CodeGenerator\Application\Interfaces\GeneratorInterface;
use function Symfony\Component\String\u;

class ContainerConfigGenerator implements GeneratorInterface
{

    private string $template = __DIR__ . '/../../resources/templates/cli-command.tpl.php';
    private string $cofigFilePath = '/resources/config/services/main.php';

    public function __construct(protected GenerateResultCollection $collection, private string $namespace, string $template = null, string $cofigFilePath = null)
    {
        if($template) {
            $this->template;
        }
        if($cofigFilePath) {
            $this->cofigFilePath = $cofigFilePath;
        }
    }

    public function init(string $namespace): void
    {
        $codeForAppend = <<<EOF
    \$services
        ->load('$namespace\\\\', __DIR__ . '/../../..')
        ->exclude([
            __DIR__ . '/../../../{resources,Domain,Application/Commands,Application/Queries}',
            __DIR__ . '/../../../**/*{Event.php,Helper.php,Message.php,Task.php,Relation.php,Normalizer.php}',
            __DIR__ . '/../../../**/{Dto,Enums}',
        ]);
EOF;
        $configFile = ComposerHelper::getPsr4Path($this->namespace) . $this->cofigFilePath;
        $templateFile = __DIR__ . '/../../resources/templates/container-config.tpl.php';
        $configGenerator = new PhpConfigGenerator($this->collection, $configFile, $templateFile);
        if (!$configGenerator->hasCode($codeForAppend)) {
            $code = $configGenerator->appendCode($codeForAppend);
        }
        if(!empty($code)) {
            $this->collection->addFile(new FileResult($configFile, $code));
        }
    }

    public function generate(string $abstractClassName, string $concreteClassName, array $args = null, array $tags = null, string $method = 'set'): void
    {
        $codeForAppend = $this->generateDefinition($abstractClassName, $concreteClassName, $args, $tags, $method);
        $configFile = ComposerHelper::getPsr4Path($this->namespace) . $this->cofigFilePath;
        $templateFile = __DIR__ . '/../../resources/templates/container-config.tpl.php';
        $configGenerator = new PhpConfigGenerator($this->collection, $configFile, $templateFile);
        if (!$configGenerator->hasCode($concreteClassName)) {
            $code = $configGenerator->appendCode($codeForAppend);
        }
        if(!empty($code)) {
            $this->collection->addFile(new FileResult($configFile, $code));
        }
    }

    private function generateDefinition(string $abstractClassName, string $concreteClassName, array $args = null, array $tags = null, string $method = 'set'): string {
        $codeForAppend = '    $services->' . $method . '(\\' . $abstractClassName . '::class, \\' . $concreteClassName . '::class)';
        if ($args) {
            $argsCode = '';
            foreach ($args as $arg) {
                $argsCode .= "\t\t\t";
                if($this->isClass($arg)) {
                    $className = u($arg)->ensureStart('\\')->toString();
                    $argsCode .= "service({$className}::class)";
                } else {
                    $argsCode .= "{$arg}";
                }
                $argsCode .= ",\n";
            }
            $codeForAppend .= '
        ->args([
            ' . trim($argsCode) . '
        ])';
        }

        if($tags) {
            foreach ($tags as $tag) {
                if(is_string($tag)) {
                    $codeForAppend .= '
        ->tag(\''.$tag.'\')';
                } else {
                    $data = (new Store('php'))->encode($tag['data']);
                    $dataLines = explode(PHP_EOL, $data);
                    foreach ($dataLines as &$line) {
                        $line = "\t\t" . $line;
                    }
                    $data = trim(implode(PHP_EOL, $dataLines));
                    $codeForAppend .= '
        ->tag(\''.$tag['name'].'\', '.$data.')';
                }
            }
        }

        $codeForAppend .= ';';
        return $codeForAppend;
    }
    public function isClass(string $name): bool
    {
        $isMatch = preg_match('/^[a-zA-Z_\x80-\xff\\\][a-zA-Z0-9_\x80-\xff\\\]*$/i', $name);
        return is_string($name) && ($isMatch || class_exists($name));
    }
}
