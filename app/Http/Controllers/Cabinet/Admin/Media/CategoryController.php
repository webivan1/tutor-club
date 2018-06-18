<?php

namespace App\Http\Controllers\Cabinet\Admin\Media;

use App\Entity\Admin\Media\Category;
use App\Http\Requests\Cabinet\Admin\Media\Category\CreateRequest;
use App\Http\Requests\Cabinet\Admin\Media\Category\UpdateRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Components\Sort;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Category $model
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Category $model)
    {
        list($models, $sort) = $model->listData($request, 10, ['id' => Sort::SORT_DESC]);

        return view('cabinet.admin.media.category.index', compact('models', 'sort'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $statuses = Category::statusesLabels();

        return view('cabinet.admin.media.category.create', compact('statuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        $category = Category::create($request->only(['slug', 'title', 'description', 'heading', 'content', 'status']));

        return redirect()->route('cabinet.admin.media.category.index')
            ->with('success', 'Категория новостей успешно добавлена!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Category $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $statuses = Category::statusesLabels();
        return view('cabinet.admin.media.category.update', compact('category', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest $request
     * @param  Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Category $category)
    {
        $category->update($request->only(['slug', 'title', 'description', 'heading', 'content', 'status'])); // что обновлять?

        return redirect()
            ->route('cabinet.admin.media.category.edit', $category)
            ->with('success', 'Успешно обновлено!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Category $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('success', 'Успешно удалено!');
    }
}