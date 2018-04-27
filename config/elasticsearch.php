<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 26.04.2018
 * Time: 0:36
 */

return [
    'hosts' => explode(',', env('ELASTICSEARCH_HOST')),
    'retries' => env('ELASTICSEARCH_RETRIES'),
];