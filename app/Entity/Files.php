<?php

namespace App\Entity;

use App\Events\Profile\FilesEvent;
use App\Services\File\Preset;
use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    /**
     * @var string
     */
    protected $table = 'files';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'filename',
        'file_path',
        'source',
        'source_id',
    ];

    /**
     * @var array
     */
    public $preset = [
        'advert' => [
            '100x150',
            '200',
            '350'
        ]
    ];

    /**
     * {@inheritdoc}
     */
    public static function boot()
    {
        parent::boot();
        self::observe(new FilesEvent());
    }

    /**
     * @param string $presetName
     * @return string
     */
    public function getPreset(string $presetName = ''): string
    {
        $preset = new Preset($this->file_path);

        return $preset->exist($presetName)
            ? $preset->presetFilename($presetName)
            : $this->file_path;
    }
}
