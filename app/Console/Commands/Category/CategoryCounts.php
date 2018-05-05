<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 29.04.2018
 * Time: 19:35
 */

namespace App\Console\Commands\Category;

use App\Entity\Category;
use App\Entity\CategoryCounts as CountsModel;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class CategoryCounts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'category:update-count {categoryId?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update countes';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        foreach (\LaravelLocalization::getSupportedLanguagesKeys() as $lang) {
            /** @var Builder $query */
            $query = Category::select(['category.*'])
                ->joinChild()
                ->joinAdvertItems($lang);

            if ($this->argument('categoryId')) {
                $query->where('category.id', $this->argument('categoryId'));
            }

            /** @var Category $category */
            foreach ($query->cursor() as $category) {
                CountsModel::updateOrCreate([
                    'category_id' => $category->id,
                    'lang' => $lang
                ], [
                    'total_categories' => $category->total_categories,
                    'total_adverts' => $category->total_adverts
                ]);
            }
        }
    }
}