<?php

namespace Vayes\Enum;

use ReflectionException;

/**
 * BasicEnum
 *
 * @author Brian Gebel <brian@pixeloven.com>
 * @link https://github.com/pixeloven/BasicEnum-PHP
 * @copyright 2015 PixelOven (Under MIT License)
 *
 * Allows for basic enumeration in PHP
 *
 */

abstract class BaseEnum {

    // Static Variables
    private static $constCacheArray = NULL;

    /**
     * Returns a value array
     *
     * @return array
     */
    public static function getOptions(): array
    {
        return array_values(self::getConstants());
    }

    /**
     * Checks if name exists in enum
     *
     * @param      $name
     * @param bool $strict
     * @return bool
     */
    public static function isValidName($name, $strict = false)
    {
        $constants = self::getConstants();

        if ($strict) {
            return array_key_exists($name, $constants);
        }

        $keys = array_map('strtolower', array_keys($constants));

        return in_array(strtolower($name), $keys);
    }

    /**
     * Checks if value exists in enum
     *
     * @param $value
     * @return bool
     */
    public static function isValidValue($value)
    {
        $values = array_values(self::getConstants());

        return in_array($value, $values, $strict = true);
    }

    /**
     * Returns value with a given name
     *
     * @param $name
     * @return bool|mixed
     */
    public static function getValueByName($name)
    {
        $constants = self::getConstants();

        $name = strtoupper($name);

        if (array_key_exists($name, $constants)) {
            return $constants[$name];
        }

        return false;
    }

    /**
     * Returns the name of the constant with a given value
     * If Enum has non-unqiue values then the first instance is returned
     * Should have it return array of constants with given value
     *
     * @param string $value
     * @return mixed
     */
    public static function getNameByValue($value)
    {
        $constants = self::getConstants();

        $flip = array_flip($constants);

        if (array_key_exists($value, $flip)) {
            return $flip[$value];
        }

        return false;
    }

    /**
     * Gets constant from defined enum
     *
     * @return mixed
     */
    private static function getConstants()
    {
        if (self::$constCacheArray == NULL) {
            self::$constCacheArray = [];
        }

        $calledClass = get_called_class();

        if (!array_key_exists($calledClass, self::$constCacheArray)) {
            try {
                $reflect = new \ReflectionClass($calledClass);
            } catch (ReflectionException $e) {
                throw new $e;
            }
            self::$constCacheArray[$calledClass] = $reflect->getConstants();
        }

        return self::$constCacheArray[$calledClass];
    }
}
