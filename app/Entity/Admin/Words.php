<?php

namespace App\Entity\Admin;

use App\Entity\Words as Base;
use LaravelLocalization;

class Words extends Base
{
    /**
     * Create localization files
     *
     * @return void
     */
    public function generateFiles(): void
    {
        foreach (LaravelLocalization::getSupportedLanguagesKeys() as $lang) {
            $data = self::where('lang', $lang)
                ->select(['w.translate', 'k.name'])
                ->from($this->getTable() . ' as w')
                ->join((new Keywords)->getTable() . ' as k', 'k.id', 'w.word_key_id')
                ->pluck('translate', 'name')
                ->toArray();

            if (!empty($data)) {
                // create php files
                $this->generatePhpFiles($data, $lang);
            }
        }
    }

    /**
     * Create php files
     *
     * @param array $data
     * @param string $lang
     */
    private function generatePhpFiles(array &$data, string $lang): void
    {
        $baseDirLang = base_path('resources' . DIRECTORY_SEPARATOR . 'lang');
        $dirLang = $baseDirLang . DIRECTORY_SEPARATOR . $lang;

        if (!is_dir($dirLang)) {
            mkdir($dirLang, 0777);
        }

        $file = $dirLang . DIRECTORY_SEPARATOR . 'home.php';

        file_put_contents($file, "<?php\n return " . var_export($data, true) . ";");
    }
}
