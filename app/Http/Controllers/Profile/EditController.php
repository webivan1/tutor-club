<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 13.04.2018
 * Time: 17:05
 */

namespace App\Http\Controllers\Profile;

use App\Entity\User;
use App\Events\Chat\ChangeDialog;
use App\Http\Controllers\Controller;
use App\Http\Requests\Profile\EditUserRequest;
use App\Jobs\ImagickJob;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;

class EditController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        return view('profile.edit.form', [
            'user' => $request->user()
        ]);
    }

    /**
     * @param EditUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function change(EditUserRequest $request)
    {
        $user = $request->user();

        if ($request->hasFile('photo')) {
            !$user->image ?: $user->image->delete();

            $this->createFile(
                $user, $this->uploadPhoto($user, $request->file('photo'))
            );
        }

        $user->name = $request->input('name');
        $user->save();

        return back()->with('success', t('You have successfully changed the data'));
    }

    /**
     * Action delete image
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function deleteImage(Request $request)
    {
        !$request->user()->image ?: $request->user()->image->delete();

        return back()->with('success', t('You deleted photo'));
    }

    /**
     * @param User $user
     * @param UploadedFile $file
     * @return false|string
     */
    private function uploadPhoto(User $user, UploadedFile $file)
    {
        // save image
        $pathLocalFile = \Storage::disk('public')->putFile("images/upload/profile/{$user->id}", $file);

        // generate presets
        ImagickJob::dispatch($pathLocalFile, [
            '150x150', '200x200', '300x300', '350x350', '400'
        ]);

        return $pathLocalFile;
    }

    /**
     * @param User $user
     * @param string $pathFile
     */
    private function createFile(User $user, string $pathFile)
    {
        $file = $user->image()->create([
            'filename' => basename($pathFile),
            'file_path' => '/' . $pathFile,
            'source' => 'user',
            'source_id' => $user->id
        ]);

        $user->image()->associate($file);
    }
}