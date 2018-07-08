<?php

namespace App\Jobs;

use App\Services\File\FileImagick;
use App\Services\File\Preset;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class ImagickJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Кол-во попыток
     *
     * @var integer
     */
    public $tries = 1;

    /**
     * @var string
     */
    private $path;

    /**
     * @var array
     */
    private $presets;

    /**
     * ImagickJob constructor.
     * @param string $path
     * @param array $presets
     */
    public function __construct(string $path, array $presets)
    {
        $this->path = $path;
        $this->presets = $presets;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $imagick = new FileImagick(
                new Preset($this->path), $this->presets
            );

            $imagick->generate();
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }
}
