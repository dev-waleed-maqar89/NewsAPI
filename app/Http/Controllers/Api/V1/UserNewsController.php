<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Main\NewsResource;
use App\Http\Traits\ApiResponseTrait;
use App\Models\Like;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserNewsController extends Controller
{
    use ApiResponseTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allNews = NewsResource::collection(News::published()->paginate(10));
        return $this->apiSuccess(compact('allNews'));
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $news = News::published()->find($id);
        if (!$news) {
            $msg = 'No news for such ID';
            return $this->apiError($msg, 404);
        }
        $news = new NewsResource($news);
        return $this->apiSuccess(compact('news'));
    }

    public function like($id)
    {
        $news = News::published()->find($id);
        if (!$news) {
            $msg = 'No news for such ID';
            return $this->apiError($msg, 404);
        }
        $like = Like::where('user_id', Auth::user()->id)->where('news_id', $id)->first();
        if ($like) {
            $msg = 'This article already added in user favourites';
            return $this->apiSuccess([], $msg, 200);
        }
        Like::create([
            'user_id' => Auth::user()->id,
            'news_id' => $id
        ]);
        $msg = 'Article was added to user favourites';
        return $this->apiSuccess([], $msg, 200);
    }
}