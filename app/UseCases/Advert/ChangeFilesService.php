<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 20.04.2018
 * Time: 11:49
 */

namespace App\UseCases\Advert;

use App\Entity\Advert\Advert;
use App\Entity\Advert\AdvertGallery;
use App\Entity\Files;
use App\Events\Advert\ChangeAdvert;
use Illuminate\Http\UploadedFile;

class ChangeFilesService
{
    /**
     * @param Advert $advert
     * @param UploadedFile[]|null $photos
     */
    public function upload(Advert $advert, array $photos)
    {
        $itemFiles = $advert->files()->count();
        $maxFiles = AdvertGallery::MAX_FILES;

        if ($itemFiles + count($photos) > $maxFiles) {
            throw new \DomainException(t('home.errorUploadMaxPhoto', [
                'max' => $maxFiles,
                'upload' => $maxFiles - ($itemFiles + count($photos))
            ]));
        }

        foreach ($photos as $photo) {
            $this->saveFile($photo, $advert);
        }
    }

    /**
     * @param UploadedFile $file
     * @param Advert $advert
     */
    private function saveFile(UploadedFile $file, Advert $advert)
    {
        $path = $file->store("images/upload/adverts/" . $advert->id);

        $advert->files()->save(Files::create([
            'filename' => basename($path),
            'file_path' => '/' . $path,
            'source' => 'advert',
            'source_id' => $advert->id
        ]));

        Advert::updated(function (Advert $advert) {
            // delete index from elastic
            event(new ChangeAdvert($advert, ChangeAdvert::EVENT_UPDATE));
        });

        // При добавлении нового фото
        // сбрасываем статус активного объявления на черновик
        $advert->toStatusDraft();
    }

    /**
     * @param Advert $advert
     * @param Files $file
     */
    public function destroy(Advert $advert, Files $file)
    {
        $advert->files()->detach($file);
        $file->delete();
    }
}