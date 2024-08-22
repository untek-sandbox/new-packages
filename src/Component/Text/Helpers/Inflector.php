<?php

namespace Untek\Component\Text\Helpers;

use Untek\Core\Contract\Common\Exceptions\InvalidMethodParameterException;
use Yiisoft\Strings\Inflector as YiiInflector;

class Inflector
{

    public const CAMEL_CASE = 'CAMEL_CASE';
    public const SNACK_CASE = 'SNACK_CASE';
    public const KEBAB_CASE = 'KEBAB_CASE';

    /*public static function toCase(string $name, string $case): string
    {
        if ($case == self::CAMEL_CASE) {
            return self::variablize($name);
        }
        if ($case == self::SNACK_CASE) {
            return self::underscore($name);
        }
        if ($case == self::KEBAB_CASE) {
            return self::camel2id(self::camelize($name));
        }
        throw new InvalidMethodParameterException('Unsupported ' . $case);
    }*/

    /**
     * Converts an underscored or CamelCase word into a English
     * sentence.
     * @param string $words
     * @param bool $ucAll whether to set all words to uppercase
     * @return string
     */
    public static function titleize($words, $ucAll = false)
    {
        return (new YiiInflector)->toSentence($words, $ucAll);
    }

    /**
     * Returns given word as CamelCased.
     *
     * Converts a word like "send_email" to "SendEmail". It
     * will remove non alphanumeric character from the word, so
     * "who's online" will be converted to "WhoSOnline".
     * @param string $word the word to CamelCase
     * @return string
     * @see variablize()
     */
    public static function camelize($word)
    {
        return (new YiiInflector)->toCamelCase($word);
    }

    /**
     * Converts a CamelCase name into space-separated words.
     * For example, 'PostTag' will be converted to 'Post Tag'.
     * @param string $name the string to be converted
     * @param bool $ucwords whether to capitalize the first letter in each word
     * @return string the resulting words
     */
    /*public static function camel2words($name, $ucwords = true)
    {
        return (new YiiInflector)->toWords($name);

    }*/

    /**
     * Converts a CamelCase name into an ID in lowercase.
     * Words in the ID may be concatenated using the specified character (defaults to '-').
     * For example, 'PostTag' will be converted to 'post-tag'.
     * @param string $name the string to be converted
     * @param string $separator the character used to concatenate the words in the ID
     * @param bool|string $strict whether to insert a separator between two consecutive uppercase chars, defaults to false
     * @return string the resulting ID
     */
    public static function camel2id($name, $separator = '-', $strict = false)
    {
        return (new YiiInflector)->pascalCaseToId($name, $separator, $strict);
    }

    /**
     * Converts an ID into a CamelCase name.
     * Words in the ID separated by `$separator` (defaults to '-') will be concatenated into a CamelCase name.
     * For example, 'post-tag' is converted to 'PostTag'.
     * @param string $id the ID to be converted
     * @param string $separator the character used to separate the words in the ID
     * @return string the resulting CamelCase name
     */
    /*public static function id2camel($id, $separator = '-')
    {
        return (new YiiInflector)->toCamelCase($id, $separator);
    }*/

    /**
     * Converts any "CamelCased" into an "underscored_word".
     * @param string $words the word(s) to underscore
     * @return string
     */
    public static function underscore($words)
    {
        // ссылка на источник функции https://stackoverflow.com/questions/1993721/how-to-convert-pascalcase-to-pascal-case
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $words, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        return implode('_', $ret);
//        return mb_strtolower(preg_replace('/(?<=\\pL)(\\p{Lu})/u', '_\\1', $words), self::encoding());
    }

    /**
     * Returns a human-readable string from $word.
     * @param string $word the string to humanize
     * @param bool $ucAll whether to set all words to uppercase or not
     * @return string
     */
    /*public static function humanize($word, $ucAll = false)
    {
        return (new YiiInflector)->toHumanReadable($word, $ucAll);
    }*/

    /**
     * Same as camelize but first char is in lowercase.
     *
     * Converts a word like "send_email" to "sendEmail". It
     * will remove non alphanumeric character from the word, so
     * "who's online" will be converted to "whoSOnline".
     * @param string $word to lowerCamelCase
     * @return string
     */
    public static function variablize($word)
    {
        return (new YiiInflector)->toCamelCase($word);
    }

    /**
     * Converts a class name to its table name (pluralized) naming conventions.
     *
     * For example, converts "Person" to "people".
     * @param string $className the class name for getting related table_name
     * @return string
     */
    public static function tableize($className)
    {
        return (new YiiInflector)->classToTable($className);
    }

    /**
     * @return bool if intl extension is loaded
     */
    /*protected static function hasIntl()
    {
        return extension_loaded('intl');
    }*/

    /**
     * Converts a table name to its class name.
     *
     * For example, converts "people" to "Person".
     * @param string $tableName
     * @return string
     */
    /*public static function classify($tableName)
    {
        return static::camelize(Pluralizer::singularize($tableName));
    }*/

    /**
     * Converts number to its ordinal English form. For example, converts 13 to 13th, 2 to 2nd ...
     * @param int $number the number to get its ordinal value
     * @return string
     */
    /*public static function ordinalize($number)
    {
        if (in_array($number % 100, range(11, 13))) {
            return $number . 'th';
        }
        switch ($number % 10) {
            case 1:
                return $number . 'st';
            case 2:
                return $number . 'nd';
            case 3:
                return $number . 'rd';
            default:
                return $number . 'th';
        }
    }*/

    /**
     * Converts a list of words into a sentence.
     *
     * Special treatment is done for the last few words. For example,
     *
     * ```php
     * $words = ['Spain', 'France'];
     * echo Inflector::sentence($words);
     * // output: Spain and France
     *
     * $words = ['Spain', 'France', 'Italy'];
     * echo Inflector::sentence($words);
     * // output: Spain, France and Italy
     *
     * $words = ['Spain', 'France', 'Italy'];
     * echo Inflector::sentence($words, ' & ');
     * // output: Spain, France & Italy
     * ```
     *
     * @param array $words the words to be converted into an string
     * @param string $twoWordsConnector the string connecting words when there are only two
     * @param string $lastWordConnector the string connecting the last two words. If this is null, it will
     * take the value of `$twoWordsConnector`.
     * @param string $connector the string connecting words other than those connected by
     * $lastWordConnector and $twoWordsConnector
     * @return string the generated sentence
     * @since 2.0.1
     */
    /*public static function sentence(array $words, $twoWordsConnector = null, $lastWordConnector = null, $connector = ', ')
    {
        if ($twoWordsConnector === null) {
            $twoWordsConnector = \Yii::t('yii', ' and ');
        }
        if ($lastWordConnector === null) {
            $lastWordConnector = $twoWordsConnector;
        }
        switch (count($words)) {
            case 0:
                return '';
            case 1:
                return reset($words);
            case 2:
                return implode($twoWordsConnector, $words);
            default:
                return implode($connector, array_slice($words, 0, -1)) . $lastWordConnector . end($words);
        }
    }*/

    /**
     * @return string
     */
    /*private static function encoding()
    {
        return 'UTF-8'; //return isset(Yii::$app) ? Yii::$app->charset : 'UTF-8';
    }*/

}
