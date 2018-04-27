<?php

namespace App\Http\Controllers\Cabinet\Admin;

use App\Components\Sort;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Entity\Admin\TutorProfile;

class TutorProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param TutorProfile $model
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, TutorProfile $model)
    {
        /**
         * @var array $models
         * @var Sort $sort
         */
        list($models, $sort) = $model->listData($request, 10, ['id' => Sort::SORT_DESC]);

        return view('cabinet.admin.tutor.index', compact('model', 'models', 'sort'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param TutorProfile $tutor
     * @return \Illuminate\Http\Response
     */
    public function edit(TutorProfile $tutor)
    {
        return view('cabinet.admin.tutor.update', compact('tutor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request  $request
     * @param TutorProfile $tutor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TutorProfile $tutor)
    {
        $tutor->update($request->only('status', 'comment'));
        return redirect()->route('cabinet.admin.tutor.index')
            ->with('success', 'Успешно обновлено!');
    }
}
