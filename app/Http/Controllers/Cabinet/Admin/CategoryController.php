<?php

namespace App\Http\Controllers\Cabinet\Admin;

use App\Entity\Admin\Category;
use App\Entity\Attribute;
use App\Events\Category\ChangeCategory;
use App\Http\Requests\Cabinet\Admin\Category\CreateRequest;
use App\Http\Requests\Cabinet\Admin\Category\UpdateRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $models = Category::withDepth()->defaultOrder()->having('depth', '=', 0)->get();
        return view('cabinet.admin.category.index', compact('models'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $parent = $request->has('id')
            ? Category::findOrFail((int) $request->input('id'))
            : null;

        return view('cabinet.admin.category.create', compact('parent'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        Category::created(function (Category $category) {
            event(new ChangeCategory($category, ChangeCategory::EVENT_CREATE));
        });

        $category = Category::create($request->only(
            'name', 'slug', 'title', 'description', 'parent_id', 'content'
        ));

        return redirect()->route('cabinet.admin.category.show', $category)
            ->with('success', 'Успешно добавлено!');
    }

    /**
     * Display the specified resource.
     *
     * @param Category $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return view('cabinet.admin.category.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Category $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $list = Category::withDepth()->defaultOrder()->where('id', '<>', $category->id)->get();
        $parent = $category->parent;
        return view('cabinet.admin.category.update', compact('category', 'list', 'parent'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Category $category)
    {
        Category::updated(function (Category $category) {
            event(new ChangeCategory($category, ChangeCategory::EVENT_UPDATE));
        });

        $category->update($request->only(['name', 'slug', 'title', 'description', 'content']));

        if ($request->input('parent_id')) {
            $parent = Category::findOrFail($request->input('parent_id'));
            $parent->appendNode($category);
        } else {
            // root category
            $category->update(['parent_id' => null]);
        }

        return redirect()->route('cabinet.admin.category.show', $category)
            ->with('success', 'Успешно обновлено!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Category $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        Category::deleted(function (Category $category) {
            event(new ChangeCategory($category, ChangeCategory::EVENT_DELETE));
        });

        $category->delete();

        return back()->with('success', 'Успешно удалено!');
    }

    /**
     * @param Category $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function first(Category $category)
    {
        if ($first = $category->siblings()->defaultOrder()->first()) {
            $category->insertBeforeNode($first);
        }

        return back();
    }

    /**
     * @param Category $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function up(Category $category)
    {
        $category->up();
        return back();
    }

    /**
     * @param Category $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function down(Category $category)
    {
        $category->down();
        return back();
    }

    /**
     * @param Category $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function last(Category $category)
    {
        if ($last = $category->siblings()->defaultOrder('desc')->first()) {
            $category->insertAfterNode($last);
        }

        return back();
    }
}
