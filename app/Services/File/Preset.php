<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 20.04.2018
 * Time: 16:02
 */

namespace App\Services\File;


class Preset
{
    /**
     * @var string
     */
    private $path;

    /**
     * Preset constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $presetName
     * @return string
     */
    public function presetFilename(string $presetName): string
    {
        $filename = $presetName . '_' . basename($this->path);
        return dirname($this->path) . '/' . $filename;
    }

    /**
     * @param string $presetName
     * @return bool
     */
    public function exist(string $presetName): bool
    {
        return is_file(public_path(ltrim($this->presetFilename($presetName), '/')));
    }

    /**
     * @return array|null
     */
    public function getAllPresets(): ?array
    {
        $mask = public_path(ltrim(dirname($this->path) . '/*_' . basename($this->path), '/'));
        $scanFiles = glob($mask);
        return empty($scanFiles) ? null : $scanFiles;
    }
}