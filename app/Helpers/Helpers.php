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

        if (empty($key)) {
            return $key;
        }

        if (!preg_match('/^[a-z]+\./', $key)) {
            $key = 'home.' . $key;
        }

        $message = $messageOriginal = __($key, $replace, $locale);


        if (!empty($replace)) {
            foreach ($replace as $k => $v) {
                $message = str_replace($v, ':' . $k, $message);
            }
        }

        // not translated
        if ($message === $key) {
            if (strpos($message, 'home.') === 0) {
                array_push($fails, str_replace('home.', '', $message));
            }
        }

        return $messageOriginal;
    }
}

if (!function_exists('chunk_column')) {
    /**
     * Разбиваем массив данных на колонки
     *
     * @param array $data
     * @param int $columns
     * @return string|array|null
     */
    function chunk_column(array $data, int $columns): array
    {
        return array_chunk($data, ceil(count($data) / $columns));
    }
}