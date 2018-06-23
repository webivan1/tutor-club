<?php

namespace App\Http\Controllers\Cabinet\Admin\Media;

use App\Entity\Admin\Media\News;
use App\Entity\Files;
use App\Entity\Media\Category;
use App\Http\Requests\Cabinet\Admin\Media\News\CreateRequest;
use App\Http\Requests\Cabinet\Admin\Media\News\UpdateRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Components\Sort;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Model;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param News $model
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, News $model)
    {
        list($models, $sort) = $model->listData($request, 10, ['id' => Sort::SORT_DESC]);

        return view('cabinet.admin.media.news.index', compact('models', 'sort'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $statuses = News::statusesLabels();
        $newsCategory = Category::orderBy('title')->pluck('title', 'id')->toArray();

        return view('cabinet.admin.media.news.create', compact('statuses', 'newsCategory'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CreateRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request)
    {
        /** @var Files $file */
        $file = $this->createFile(
            $this->uploadImage($request->file('photo'), 'images/upload')
        );

        if (empty($file->id)) {
            return back()->with('error', 'Не удалось сохранить файл!');
        }

        News::create(array_merge($request->all(), ['file_id' => $file->id]));

        return redirect()->route('cabinet.admin.media.news.index')
            ->with('success', 'Новость успешно добавлена!');
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
     * @param  News $news
     * @return \Illuminate\Http\Response
     */
    public function edit(News $news)
    {
        $statuses = News::statusesLabels();
        $newsCategory = Category::orderBy('title')->pluck('title', 'id')->toArray();

        return view('cabinet.admin.media.news.update', compact('news', 'statuses', 'newsCategory'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest $request
     * @param  News $news
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, News $news)
    {
        if ($request->hasFile('photo')) {
            // delete old photo
            !$news->image ?: $news->image->delete();

            // create new photo
            $file = $this->createFile(
                $this->uploadImage($request->file('photo'), 'images/upload')
            );

            $news->file_id = $file->id;
        }

        $news->update($request->all());

        return redirect()
            ->route('cabinet.admin.media.news.edit', $news)
            ->with('success', 'Успешно обновлено!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  News $news
     * @return \Illuminate\Http\Response
     */
    public function destroy(News $news)
    {
        $news->delete();
        return back()->with('success', 'Успешно удалено!');
    }

    /**
     * @param UploadedFile $file
     * @param string $path
     * @param null|string $filename
     * @return false|UploadedFile|string
     */
    public function uploadImage(UploadedFile $file, string $path, ?string $filename = null)
    {
        // save image
        $pathLocalFile = !$filename
            ? \Storage::disk('public')->putFile($path, $file)
            : \Storage::disk('public')->putFileAs($path, $file, $filename);

        return $pathLocalFile;
    }

    /**
     * @param string $file
     * @return Model
     */
    public function createFile(string $file): Model
    {
        return Files::create([
            'filename' => basename($file),
            'file_path' => '/' . $file
        ]);
    }
}