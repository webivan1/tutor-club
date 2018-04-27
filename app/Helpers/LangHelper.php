<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 19.04.2018
 * Time: 22:00
 */

namespace App\Helpers;

use LaravelLocalization;

class LangHelper
{
    /**
     * @param string $valueName
     * @return array
     */
    public static function langList(string $valueName = 'native'): array
    {
        $list = LaravelLocalization::getSupportedLocales();
        return array_combine(array_keys($list), array_column($list, $valueName));
    }
}