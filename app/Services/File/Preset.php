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
     * @return string|null
     */
    public function presetFilename(string $presetName): ?string
    {
        $filename = $presetName . '_' . basename($this->path);
        $file = dirname($this->path) . '/' . $filename;
        return is_file('.' . $file) ? $file : (is_file('.' . $this->path) ? $this->path : null);
    }

    /**
     * @param string $presetName
     * @return bool
     */
    public function exist(string $presetName): bool
    {
        return \Storage::exists($this->presetFilename($presetName));
    }

    /**
     * Получаем корень файла без пресета
     *
     * @param string $filename
     * @return string
     */
    public function getFilenameWithoutPreset(string $filename): string
    {
        if (preg_match('/^([\dx]+)_.*/', $filename)) {
            return explode('_', $filename)[1];
        }

        return $filename;
    }

    /**
     * @return array|null
     */
    public function getAllPresets(): ?array
    {
        $allFiles = \Storage::allFiles(dirname($this->path)) ?? [];

        $filename = $this->getFilenameWithoutPreset(basename($this->path));
        $presetFiles = [];

        foreach ($allFiles as $file) {
            if (strpos($file, $filename) !== false) {
                $presetFiles[] = $file;
            }
        }

        return $presetFiles;
    }
}