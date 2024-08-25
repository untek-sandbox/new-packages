<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace Untek\Component\Arr\Libs\ArrayValues;

/**
 * Object that represents the removal of array value while performing [[ExtArrayHelper::merge()]].
 *
 * Usage example:
 *
 * ```php
 * $array1 = [
 *     'ids' => [
 *         1,
 *     ],
 *     'validDomains' => [
 *         'example.com',
 *         'www.example.com',
 *     ],
 * ];
 *
 * $array2 = [
 *     'ids' => [
 *         2,
 *     ],
 *     'validDomains' => new \Untek\Core\Base\Legacy\Yii\Helpers\UnsetArrayValue(),
 * ];
 *
 * $result = \Untek\Component\Arr\Helpers\ExtArrayHelper::merge($array1, $array2);
 * ```
 *
 * The result will be
 *
 * ```php
 * [
 *     'ids' => [
 *         1,
 *         2,
 *     ],
 * ]
 * ```
 *
 * @author Robert Korulczyk <robert@korulczyk.pl>
 * @since 2.0.10
 */
class UnsetArrayValue
{
    /**
     * Restores class state after using `var_export()`.
     *
     * @param array $state
     * @return UnsetArrayValue
     * @see var_export()
     * @since 2.0.16
     */
    public static function __set_state($state)
    {
        return new self();
    }
}
