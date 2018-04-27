<?php

namespace App\Http\Controllers\Cabinet\Admin;

use App\Entity\Attribute;
use App\Entity\Admin\Category;
use App\Http\Requests\Cabinet\Admin\Attribute\CreateRequest;
use App\Http\Requests\Cabinet\Admin\Attribute\UpdateRequest;
use App\Http\Controllers\Controller;

class AttributeController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @param Category $category
     * @return \Illuminate\Http\Response
     */
    public function create(Category $category)
    {
        return view('attribute.create', compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateRequest $request
     * @param Category $category
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request, Category $category)
    {
        /** @var Attribute $model */
        $model = Attribute::make($request->all());
        $model->category()->associate($category);
        $model->saveOrFail();

        return redirect()->route('cabinet.admin.category.show', $category)
            ->with('success', 'Успешно добавлен атрибут!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Category $category
     * @param Attribute $attribute
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category, Attribute $attribute)
    {
        return view('attribute.update', compact('category', 'attribute'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param Category $category
     * @param Attribute $attribute
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Category $category, Attribute $attribute)
    {
        $attribute->update($request->all());

        return back()->with('success', 'Успешно обновлен атрибут!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Category $category
     * @param Attribute $attribute
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category, Attribute $attribute)
    {
        $attribute->delete();
        return back()->with('success', 'Успешно удален атрибут!');
    }
}
