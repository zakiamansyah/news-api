<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'image_url' => asset('storage/' . $this->image_path),
            'author' => new UserResource($this->user),
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
