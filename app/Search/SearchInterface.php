<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 05.04.2018
 * Time: 14:25
 */

namespace App\Search;


interface SearchInterface
{
    /**
     * Rules validation
     *
     * @return array
     */
    public function rules(): array;

    /**
     * Call is valid attributes for relations query builder
     *
     * @return array
     */
    public function withQuery(): array;
}