<?php

namespace App\Http\Controllers\Cabinet\Admin;

use App\Entity\Admin\Keywords;
use App\Entity\Admin\Words;
use App\Http\Requests\Cabinet\Admin\Translate\CreateRequest;
use App\Http\Requests\Cabinet\Admin\Translate\UpdateRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Components\Sort;
use LaravelLocalization;

class TranslateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Keywords $model
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Keywords $model)
    {
        /**
         * @var array $models
         * @var Sort $sort
         */
        list($models, $sort) = $model->listData($request, 10, ['id' => Sort::SORT_DESC]);
        $languages = LaravelLocalization::getSupportedLocales();

        return view('cabinet.admin.translate.index', compact('models', 'sort', 'languages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $languages = LaravelLocalization::getSupportedLocales();
        return view('cabinet.admin.translate.create', compact('languages'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        $wordKey = Keywords::create($request->only('name'));

        foreach ($request->input('translate') as $lang => $translate) {
            $wordKey->words()->create(compact('lang', 'translate'));
        }

        return redirect()->route('cabinet.admin.translate.index')
            ->with('success', 'Перевод успешно добавлен!');
    }

    /**
     * Display the specified resource.
     *
     * @param Keywords $wordKey
     * @return \Illuminate\Http\Response
     */
    public function show(Keywords $wordKey)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Keywords $translate
     * @return \Illuminate\Http\Response
     */
    public function edit(Keywords $translate)
    {
        $langs = LaravelLocalization::getSupportedLocales();
        $translated = $translate->words()->pluck('translate', 'lang')->toArray();

        return view('cabinet.admin.translate.update', compact('langs', 'translate', 'translated'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param Keywords $translate
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Keywords $translate)
    {
        $translate->update($request->only('name'));

        foreach ($request->input('translate') as $lang => $text) {
            $word = Words::firstOrCreate([
                'word_key_id' => $translate->id,
                'lang' => $lang
            ], ['translate' => $text]);
            $word->translate = $text;
            $word->save();
        }

        return back()->with('success', 'Успешно обновлено!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Keywords $translate
     * @return \Illuminate\Http\Response
     */
    public function destroy(Keywords $translate)
    {
        Words::where('word_key_id', $translate->id)->delete();
        $translate->delete();

        return back()->with('success', 'Успешно удалено!');
    }

    /**
     * Generate translate files
     *
     * @param Words $words
     * @return \Illuminate\Http\Response
     */
    public function generate(Words $words)
    {
        $words->generateFiles();
        return back()->with('success', 'Файлы успешно созданы!');
    }
}
