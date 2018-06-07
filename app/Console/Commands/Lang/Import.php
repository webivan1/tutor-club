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
     * @var array
     */
    private $data = [];

    /**
     * @var int
     */
    private $limit = 100;

    /**
     * Create a new command instance.
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

                while($generator->valid()) {
                    $this->add($generator->key(), $generator->current());
                    $generator->next();

                    if (count($this->data) >= $this->limit || !$generator->valid()) {
                        $this->import($lang);
                    }
                }
            } catch (\Exception $e) {
                $this->error($e->getMessage());
            }
        }

        $this->info('OK!');
    }

    /**
     * Импортируем часть данных в базу
     *
     * @param string $lang
     * @return void
     */
    private function import(string $lang): void
    {
        if (empty($this->data)) {
            return;
        }

        $data = [];

        array_map(function ($value, $key) use (&$data) {
            $data[mb_strtolower($key, 'utf8')] = $value;
        }, $this->data, array_keys($this->data));

        $data = array_combine($this->syncKeys(array_keys($data)), $data);

        $this->syncWords($data, $lang);
    }

    /**
     * Синхронизируем тексты с базой
     *
     * @param array $words
     * @param string $lang
     */
    private function syncWords(array $words, string $lang): void
    {
        // Очищаем массив $this->data
        $this->clean();

        // Список ключей в базе
        $listWords = Words::whereIn('word_key_id', array_keys($words))
            ->where('lang', $lang)
            ->get()
            ->pluck('translate', 'word_key_id')
            ->toArray();

        foreach ($words as $key => $word) {
            if (!array_key_exists($key, $listWords)) {
                Words::create([
                    'word_key_id' => $key,
                    'lang' => $lang,
                    'translate' => $word
                ]);
            } else if ($listWords[$key] !== $word) {
                $model = Words::where('word_key_id', $key)
                    ->where('lang', $lang)
                    ->first();

                if ($model) {
                    $model->update(['translate' => $word]);
                }
            }
        }
    }

    /**
     * Синхронизируем ключи текстов с базой
     *
     * @param array $keys
     * @return array
     */
    private function syncKeys(array $keys): array
    {
        $listKeys = Keywords::whereIn('name', $keys)
            ->get()
            ->each(function ($data) {
                $data->name = mb_strtolower($data->name, 'utf8');
            })
            ->pluck('id', 'name')
            ->toArray();

        $keysId = [];

        foreach ($keys as $key) {
            if (!array_key_exists($key, $listKeys)) {
                $model = Keywords::create(['name' => $key]);
                $id = $model->id;
            } else {
                $id = $listKeys[$key];
            }

            $keysId[$key] = $id;
        }

        return $keysId;
    }

    /**
     * Очищаем масссив
     *
     * @return void
     */
    private function clean(): void
    {
        $this->data = [];
    }

    /**
     * Добавляем в массив
     *
     * @param string $key
     * @param string $value
     * @return void
     */
    private function add(string $key, string $value): void
    {
        $this->data[$key] = $value;
    }

    /**
     * Вытаскиваем все переводы из файла
     *
     * @param $langName
     * @return \ArrayIterator
     * @throws \Exception
     */
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
