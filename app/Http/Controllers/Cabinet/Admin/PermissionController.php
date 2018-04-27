<?php

namespace App\Http\Controllers\Cabinet\Admin;

use App\Components\Sort;
use App\Http\Requests\Cabinet\Admin\Permission\CreateRequest;
use App\Http\Requests\Cabinet\Admin\Permission\UpdateRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Entity\Admin\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Permission $model
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Permission $model)
    {
        /**
         * @var array $models
         * @var Sort $sort
         */
        list($models, $sort) = $model->listData($request, 10, ['id' => Sort::SORT_DESC]);

        return view('cabinet.admin.permission.index', compact('models', 'sort'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('cabinet.admin.permission.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        $model = Permission::create($request->only(['name', 'title']));
        return redirect()->route('cabinet.admin.permission.index')
            ->with('success', 'Успешно добавлено #' . $model->id);
    }

    /**
     * Display the specified resource.
     *
     * @param Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        return abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        return view('cabinet.admin.permission.update', compact('permission'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest  $request
     * @param Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Permission $permission)
    {
        $permission->update($request->only(['name', 'title']));
        return redirect()
            ->route('cabinet.admin.permission.edit', $permission)
            ->with('success', 'Успешно обновлено!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Permission $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return back()->with('success', 'Успешно удалено!');
    }
}