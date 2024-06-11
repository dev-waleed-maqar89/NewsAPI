<?php

namespace App\Http\Resources\Main;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'article' => $this->article,
            'profile' => $this->image,
            'publishe at' => $this->published_at ?? 'Not yet',
            'relations' => [
                'author' => new AdminResource($this->admin),
                'images' => ImageResource::collection($this->images),
            ]
        ];
    }
}