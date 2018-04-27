<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 19.04.2018
 * Time: 17:03
 */

namespace App\Http\Controllers\Cabinet\Advert;

use App\Entity\Advert\Advert;
use App\Entity\Files;
use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\Advert\UploadFilesRequest;
use App\UseCases\Advert\ChangeFilesService;
use Illuminate\Http\Response;

class FilesController extends Controller
{
    /**
     * @var ChangeFilesService
     */
    private $service;

    /**
     * CreateController constructor.
     * @param ChangeFilesService $service
     */
    public function __construct(ChangeFilesService $service)
    {
        $this->middleware('can:own-update-advert,advert');

        $this->service = $service;
    }

    /**
     * View form
     *
     * @param Advert $advert
     * @return Response
     */
    public function edit(Advert $advert)
    {
        $files = $advert->files;
        return view('advert.edit.files', compact('advert', 'files'));
    }

    /**
     * Handler form
     *
     * @param UploadFilesRequest $request
     * @param Advert $advert
     * @return Response
     */
    public function update(UploadFilesRequest $request, Advert $advert)
    {
        $this->service->upload($advert, $request->file('photo'));
        return back()->with('success', t('home.uploadedPhotoSuccessAdvert'));
    }

    /**
     * Delete file
     *
     * @param Advert $advert
     * @param Files $file
     * @return Response
     */
    public function delete(Advert $advert, Files $file)
    {
        $this->service->destroy($advert, $file);
        return back()->with('success', t('home.deleteSuccessPhoto'));
    }
}