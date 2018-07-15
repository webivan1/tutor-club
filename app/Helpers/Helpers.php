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

if (!function_exists('registerBreadcrumbs')) {
    /**
     * Загружаем все конфиги хлебных крошек
     *
     * @param $dir
     * @return void
     */
    function registerBreadcrumbs($dir): void {
        foreach (scandir($dir) as $name) {
            if (strpos($name, '.') === 0) {
                continue;
            }

            if (is_dir($dir . DIRECTORY_SEPARATOR . $name)) {
                registerBreadcrumbs($dir . DIRECTORY_SEPARATOR . $name);
                continue;
            }

            $filename = $dir . DIRECTORY_SEPARATOR . $name;

            if (strrpos($filename, '.php') !== false) {
                require $filename;
            }
        }
    }
}

if (!function_exists('convertUserTimezone')) {
    /**
     * @param string $date
     * @param null|string $timezone
     * @return \Carbon\Carbon
     */
    function convertUserTimezone(string $date, ?string $timezone = null): \Carbon\Carbon {
        if (\Auth::check() && \Auth::user()->timezone) {
            $timezone = \Auth::user()->timezone;
        }

        $carbon = new \Carbon\Carbon($date, $timezone);
        $carbon->tz(date_default_timezone_get());

        return $carbon;
    }
}