<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\AddNewsImagesRequest;
use App\Http\Requests\Api\V1\NewsRequest;
use App\Http\Requests\Api\V1\NewsUpdateRequest;
use App\Http\Resources\Main\NewsResource;
use App\Http\Traits\ApiResponseTrait;
use App\Http\Traits\ImageTrait;
use App\Models\Image;
use App\Models\News;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminNewsController extends Controller
{
    use ImageTrait, ApiResponseTrait;
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'ApiAdminMiddleware']);
        $this->middleware('ApiAdminMiddleware:supervisor,moderator')->only('publish');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allNews = NewsResource::collection(News::paginate(12));
        return $this->apiSuccess(compact('allNews'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NewsRequest $request)
    {
        $id = Auth::user()->admin->id;
        $img = '';
        if ($request->hasFile('image')) {
            $img = $this->singleImageUpload($request->file('image'), 'news', $request->title);
        }
        $news = News::create(
            [
                'admin_id' => $id,
                'title' => $request->title,
                'article' => $request->article,
                'image' => $img,
            ]
        );
        $news = new NewsResource($news);
        $msg = 'News was created successfully';
        return $this->apiSuccess(compact('news'), $msg);
    }
    public function addImage(AddNewsImagesRequest $request, $id)
    {
        $news = News::find($id);
        if (!$news) {
            $msg = 'No news for such ID';
            return $this->apiError($msg, 404);
        }
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            foreach ($images as $img) {
                $img = $this->singleImageUpload($img,  'news', $news->title);
                Image::create(
                    [
                        'news_id' => $news->id,
                        'path' => $img,
                    ]
                );
            }
            $msg = 'Images added successfully';
            $news = new NewsResource($news);
            return $this->apiSuccess(compact('news'), $msg);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $news = News::find($id);
        if (!$news) {
            $msg = 'No news for such ID';
            return $this->apiError($msg, 404);
        }
        $news = new NewsResource($news);
        return $this->apiSuccess(compact('news'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(NewsUpdateRequest $request, string $id)
    {
        $news = News::find($id);
        if (!$news) {
            $msg = 'No news for such ID';
            return $this->apiError($msg, 404);
        }
        $title = $request->title ?? $news->title;
        $article = $request->article ?? $news->article;
        $img = $news->image;
        if ($request->hasFile('image')) {
            unlink(public_path($news->image));
            $img = $this->singleImageUpload($request->file('image'), 'news', $request->title);
        }
        $news->update([
            'title' => $title,
            'article' => $article,
            'image' => $img
        ]);
        $news = new NewsResource($news);
        $msg = 'News has been updated successfully';
        return $this->apiSuccess(compact('news'), $msg);
    }

    public function publish($id)
    {
        $news = News::find($id);
        if (!$news) {
            $msg = 'No news for such ID';
            return $this->apiError($msg, 404);
        }
        $date = Carbon::parse(now())->setTimeZone('Africa/cairo')->format('Y-m-d H:i');
        $news->update(
            ['published_at' => $date]
        );
        $news = new NewsResource($news);
        $msg = 'News has been published successfully';
        return $this->apiSuccess(compact('news'), $msg);
    }


    /**
     * Remove the specified resource from storage.
     */
    /**
     *
     */
    public function destroy(string $id)
    {
        $news = News::find($id);
        if (!$news) {
            $msg = 'No news for such ID';
            return $this->apiError($msg, 404);
        }
        $images = $news->images;
        foreach ($images as $image) {
            unlink(public_path($image->path));
        }
        unlink(public_path($news->image));
        $news->delete();
        $msg = 'News has been deleted Successfully';
        return $this->apiSuccess([], $msg);
    }
}