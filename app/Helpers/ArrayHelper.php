<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 18.04.2018
 * Time: 23:51
 */

namespace App\Helpers;


class ArrayHelper
{
    /**
     * @param array|null $data
     * @return array
     */
    public static function multipleDataFormToCorrectArray(?array $data): ?array
    {
        if (empty($data)) {
            return null;
        }

        $correctArray = [];

        foreach ($data as $key => $value) {
            for ($i = 0; $i < count($value); $i++) {
                $correctArray[$i][$key] = $value[$i];
            }
        }

        return $correctArray;
    }
}