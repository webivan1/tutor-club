<?php

namespace App\Http\Controllers\Cabinet\Admin;

use App\Entity\Admin\Role;
use App\Entity\Admin\User;
use App\Http\Requests\Cabinet\Admin\User\CreateRequest;
use App\Http\Requests\Cabinet\Admin\User\UpdateRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Components\Sort;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param User $model
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, User $model)
    {
        /**
         * @var array $models
         * @var Sort $sort
         */
        list($models, $sort) = $model->listData($request, 10, ['id' => Sort::SORT_DESC]);

        $roles = Role::orderBy('level')->pluck('title', 'id')->toArray();
        $statuses = User::statusesLabels();

        return view('cabinet.admin.users.index', compact(
            'models', 'sort', 'roles', 'statuses'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::orderBy('level')->pluck('title', 'id')->toArray();
        $statuses = User::statusesLabels();
        return view('cabinet.admin.users.create', compact('roles', 'statuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateRequest $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request, User $user)
    {
        /** @var Role $role */
        $role = Role::findOrFail($request->input('role'));
        $user->setRole($role);
        $user->setRawAttributes($request->only(['name', 'email', 'status']));
        $user->saveOrFail();
        return redirect()->route('cabinet.admin.users.index')
            ->with('success', 'Вы успешно создали пользователя!');
    }

    /**
     * Display the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::orderBy('level')->pluck('title', 'id')->toArray();
        $statuses = User::statusesLabels();
        return view('cabinet.admin.users.update', compact('user', 'roles', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateRequest $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, User $user)
    {
        /** @var Role $role */
        $role = Role::findOrFail($request->input('role'));
        // change role
        !$user->isChangeRole($role) ?: $user->setRole($role);
        // update attributes
        $user->updated_at = date('Y-m-d H:i:s'); // run event update
        $user->update($request->only(['name', 'email', 'status']));

        return redirect()->route('cabinet.admin.users.index')
            ->with('success', 'Успешно обновлено!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'Успешно удалено');
    }
}
