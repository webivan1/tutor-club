<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 24.04.2018
 * Time: 18:37
 */

// Functions

if (!function_exists('t')) {
    /**
     * Translate the given message.
     *
     * @param  string  $key
     * @param  array  $replace
     * @param  string  $locale
     * @return string|array|null
     */
    function t($key, $replace = [], $locale = null)
    {
        static $fails = [];

        if ($key === null) {
            return $fails;
        }

        $message = __($key, $replace, $locale);

        // not translated
        if ($message === $key) {
            if (strpos($message, 'home.') === 0) {
                array_push($fails, str_replace('home.', '', $message));
            }
        }

        return $message;
    }
}