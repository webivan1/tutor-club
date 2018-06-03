<?php

namespace App\Console\Commands\Lang;

use App\Entity\Keywords;
use App\Entity\Words;
use Illuminate\Console\Command;

class Import extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lang:import {name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $langs = \LaravelLocalization::getSupportedLanguagesKeys();

        if ($this->argument('name')) {
            $langs = array_intersect($langs, [$this->argument('name')]);
        }

        foreach ($langs as $lang) {
            try {
                $generator = $this->getContentFromFile($lang);

                foreach ($generator as $key => $value) {
                    $this->add($lang, $key, $value);
                }
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }

        $this->info('OK!');
    }

    private function add($lang, $key, $value)
    {
        $keyword = Keywords::firstOrCreate(['name' => $key]);

        Words::updateOrCreate([
            'lang' => $lang,
            'word_key_id' => $keyword->id
        ], [
            'translate' => $value
        ]);
    }

    private function getContentFromFile($langName): \ArrayIterator
    {
        $langDir = resource_path('lang' . DIRECTORY_SEPARATOR . $langName);

        if (!is_dir($langDir)) {
            throw new \Exception("Undefined path " . $langDir);
        }

        $langFile = $langDir . DIRECTORY_SEPARATOR . 'home.php';

        if (!is_file($langFile)) {
            throw new \Exception("Undefined home.php " . $langFile);
        }

        return new \ArrayIterator(require $langFile);
    }
}
