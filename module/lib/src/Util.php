<?php

/**
 * Util.php - created 12 Nov 2016 14:24:07
 *
 * @copyright Copyright (c) Mathias Schilling <m@matchilling>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */
namespace Tronald\Lib;

/**
 *
 * Util
 *
 * @package Tronald\Lib
 */
class Util
{

    /**
     * Encode a given string into an url safe representation
     *
     * @param  string $str
     * @return string
     */
    public static function base64UrlsafeEncode($str)
    {
        $data = base64_encode($str);
        $data = preg_replace('/[\=]+\z/', '', $data);
        $data = strtr($data, '+/=', '-_');

        return $data;
    }

    /**
     * Create an url safe uuid with a fixed length of 22 characters

     * @return string
     */
    public static function createSlugUuid()
    {
        $uuid = \Ramsey\Uuid\Uuid::uuid4();

        return self::base64UrlsafeEncode($uuid->getBytes());
    }

    /**
     * Gets the value of an environment variable or an optional default value
     *
     * @param  string $name
     * @param  string $default
     * @return mixed
     */
    public static function getEnvOrDefault($name, $default = null)
    {
        return getenv($name) ?: (null !== $default ? $default : null);
    }

    /**
     * Get the value of a given variable or return null
     *
     * @param  string $variable
     * @return mixed
     */
    public static function getOrNull(&$variable)
    {
        return isset($variable) ? $variable : null;
    }

    /**
     * Get the value of a given variable or an optional default value
     *
     * @param  string $variable
     * @param  string $default
     * @return mixed
     */
    public static function getOrDefault(&$variable, $default = null)
    {
        return isset($variable) ? $variable : $default;
    }

    /**
     *
     * @param  string $string
     * @param  string $separator
     * @return string
     */
    public static function slugify($string, $separator = '-')
    {
        $accents_regex = '~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i';
        $special_cases = [
            '&' => 'and',
            "'" => ''
        ];
        $string = mb_strtolower(trim($string), 'UTF-8');
        $string = str_replace(array_keys($special_cases), array_values($special_cases), $string);
        $string = preg_replace($accents_regex, '$1', htmlentities($string, ENT_QUOTES, 'UTF-8'));
        $string = preg_replace("/[^a-z0-9]/u", "$separator", $string);
        $string = preg_replace("/[$separator]+/u", "$separator", $string);

        return $string;
    }
}
