<?php

namespace App\Http\Controllers\Cabinet\Admin;

use App\Components\Sort;
use App\Entity\Admin\Lang;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\Admin\Lang\CreateRequest;
use App\Http\Requests\Cabinet\Admin\Lang\UpdateRequest;
use Illuminate\Http\Request;

class LangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Lang $model
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Lang $model)
    {
        /**
         * @var array $models
         * @var Sort $sort
         */
        list($models, $sort) = $model->listData($request, 10, ['id' => Sort::SORT_DESC]);

        return view('cabinet.admin.lang.index', compact('models', 'sort'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cabinet.admin.lang.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        $model = Lang::create($request->only([
            'name',
            'native',
            'value',
            'regional'
        ]));

        return redirect()->route('cabinet.admin.lang.index')
            ->with('success', 'Успешно добавлено #' . $model->id);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Lang $lang
     * @return \Illuminate\Http\Response
     */
    public function edit(Lang $lang)
    {
        return view('cabinet.admin.lang.update', compact('lang'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest  $request
     * @param Lang $lang
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Lang $lang)
    {
        $lang->update($request->only([
            'name',
            'value',
            'native',
            'regional'
        ]));

        return redirect()
            ->route('cabinet.admin.lang.edit', $lang)
            ->with('success', 'Успешно обновлено!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Lang $lang
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lang $lang)
    {
        $lang->delete();
        return back()->with('success', 'Успешно удалено!');
    }
}