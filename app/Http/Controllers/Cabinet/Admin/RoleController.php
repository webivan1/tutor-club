<?php

namespace App\Http\Controllers\Cabinet\Admin;

use App\Http\Requests\Cabinet\Admin\Role\CreateRequest;
use App\Http\Requests\Cabinet\Admin\Role\UpdateRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Entity\Admin\Permission;
use App\Entity\Admin\Role;
use App\Components\Sort;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Role $model
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Role $model)
    {
        /**
         * @var array $models
         * @var Sort $sort
         */
        list($models, $sort) = $model->listData($request, 10, ['level' => Sort::SORT_ASC]);
        $permissions = Permission::orderBy('name')->pluck('title', 'id');
        $permissions = ['' => 'Выбрать'] + $permissions->toArray();

        return view('cabinet.admin.role.index', compact('models', 'sort', 'permissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::orderBy('name')->get();
        return view('cabinet.admin.role.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        $role = Role::create($request->only(['name', 'title', 'level']));
        $role->changeAbilities($request->input('permissions', []));

        return redirect()->route('cabinet.admin.role.index')
            ->with('success', 'Успешно добавлено #' . $role->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Role $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('name')->get();
        $checkPermissions = $role->abilities()->pluck('id')->toArray();
        return view('cabinet.admin.role.update', compact('role', 'permissions', 'checkPermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param Role $role
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Role $role)
    {
        $role->update($request->only(['name', 'title', 'level']));
        $role->changeAbilities($request->input('permissions', []));

        return redirect()
            ->route('cabinet.admin.role.edit', $role)
            ->with('success', 'Успешно обновлено!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Role $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return back()->with('success', 'Успешно удалено!');
    }
}
