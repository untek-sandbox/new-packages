<?php

namespace Untek\Core\Text\Helpers;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\InflectorFactory;
use Symfony\Component\DependencyInjection\Container;

final class Str
{
    private static ?Inflector $inflector = null;

    /**
     * Transforms the given string into the format commonly used by PHP classes,
     * (e.g. `app:do_this-and_that` -> `AppDoThisAndThat`) but it doesn't check
     * the validity of the class name.
     */
    public static function asClassName(string $value, string $suffix = ''): string
    {
        $value = trim($value);
        $value = str_replace(['-', '_', '.', ':'], ' ', $value);
        $value = ucwords($value);
        $value = str_replace(' ', '', $value);
        $value = ucfirst($value);
        $value = self::addSuffix($value, $suffix);
        return $value;
    }

    /**
     * Transforms the given string into the format commonly used by Twig variables
     * (e.g. `BlogPostType` -> `blog_post_type`).
     */
    public static function asTwigVariable(string $value): string
    {
        $value = trim($value);
        $value = preg_replace('/[^a-zA-Z0-9_]/', '_', $value);
        $value = preg_replace('/(?<=\\w)([A-Z])/', '_$1', $value);
        $value = preg_replace('/_{2,}/', '_', $value);
        $value = strtolower($value);
        return $value;
    }

    public static function asLowerCamelCase(string $str): string
    {
        return lcfirst(self::asCamelCase($str));
    }

    public static function asCamelCase(string $str): string
    {
        return strtr(ucwords(strtr($str, ['_' => ' ', '.' => ' ', '\\' => ' '])), [' ' => '']);
    }

    public static function asRoutePath(string $value): string
    {
        return '/' . str_replace('_', '/', self::asTwigVariable($value));
    }

    public static function asRouteName(string $value): string
    {
        $routeName = self::asTwigVariable($value);
        return str_starts_with($routeName, 'app_') ? $routeName : 'app_' . $routeName;
    }

    public static function asSnakeCase(string $value): string
    {
        return self::asTwigVariable($value);
    }

    public static function asCommand(string $value): string
    {
        return str_replace('_', '-', self::asTwigVariable($value));
    }

    public static function asEventMethod(string $eventName): string
    {
        return sprintf('on%s', self::asClassName($eventName));
    }

    public static function getShortClassName(string $fullClassName): string
    {
        if (empty(self::getNamespace($fullClassName))) {
            return $fullClassName;
        }

        return substr($fullClassName, strrpos($fullClassName, '\\') + 1);
    }

    public static function getNamespace(string $fullClassName): string
    {
        return substr($fullClassName, 0, strrpos($fullClassName, '\\'));
    }

    public static function asFilePath(string $value): string
    {
        $value = Container::underscore(trim($value));
        $value = str_replace('\\', '/', $value);
        return $value;
    }

    public static function singularCamelCaseToPluralCamelCase(string $camelCase): string
    {
        $snake = self::asSnakeCase($camelCase);
        $words = explode('_', $snake);
        $words[\count($words) - 1] = self::pluralize($words[\count($words) - 1]);
        $reSnaked = implode('_', $words);
        return self::asLowerCamelCase($reSnaked);
    }

    public static function pluralCamelCaseToSingular(string $camelCase): string
    {
        $snake = self::asSnakeCase($camelCase);
        $words = explode('_', $snake);
        $words[\count($words) - 1] = self::singularize($words[\count($words) - 1]);
        $reSnaked = implode('_', $words);
        return self::asLowerCamelCase($reSnaked);
    }

    public static function getRandomTerm(): string
    {
        $adjectives = [
            'tiny',
            'delicious',
            'gentle',
            'agreeable',
            'brave',
            'orange',
            'grumpy',
            'fierce',
            'victorious',
        ];
        $nouns = [
            'elephant',
            'pizza',
            'popsicle',
            'chef',
            'puppy',
            'gnome',
            'kangaroo',
        ];
        return sprintf('%s %s', $adjectives[array_rand($adjectives)], $nouns[array_rand($nouns)]);
    }

    /**
     * @return bool
     */
    public static function areClassesAlphabetical(string $class1, string $class2)
    {
        $arr1 = [$class1, $class2];
        $arr2 = [$class1, $class2];
        sort($arr2);
        return $arr1[0] == $arr2[0];
    }

    public static function asHumanWords(string $variableName): string
    {
        return str_replace('  ', ' ', ucfirst(trim(implode(' ', preg_split('/(?=[A-Z])/', $variableName)))));
    }

    private static function pluralize(string $word): string
    {
        return static::getInflector()->pluralize($word);
    }

    private static function singularize(string $word): string
    {
        return static::getInflector()->singularize($word);
    }

    private static function getInflector(): Inflector
    {
        if (null === static::$inflector) {
            static::$inflector = InflectorFactory::create()->build();
        }
        return static::$inflector;
    }
}
