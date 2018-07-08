<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 20.04.2018
 * Time: 14:27
 */

namespace App\Services\File;


class FileImagick
{
    /**
     * @var Preset
     */
    private $preset;

    /**
     * @var array
     */
    private $presetConfig = [];

    /**
     * FileImagick constructor.
     * @param Preset $preset
     * @param array $presets
     */
    public function __construct(Preset $preset, array $presets)
    {
        $this->preset = $preset;
        $this->presetConfig = $presets;
    }

    /**
     * Generate presets
     *
     * @return void
     */
    public function generate(): void
    {
        foreach ($this->presetConfig as $preset) {
            $image = new \Imagick(public_path($this->preset->getPath()));

            $sizeImage = explode('x', $preset);

            if (count($sizeImage) > 1) {
                $image->cropThumbnailImage(...$sizeImage);
                $image->stripimage();
            } else {
                $image->thumbnailImage($sizeImage[0], 0);
            }

            $image->writeImage(public_path($this->preset->presetFilenameNotExist($preset)));
            $image->destroy();
        }
    }
}