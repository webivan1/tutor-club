<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 01.04.2018
 * Time: 18:34
 */

function registerBreadcrumbs($dir) {
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

registerBreadcrumbs(__DIR__ . '/breadcrumbs');