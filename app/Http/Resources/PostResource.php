<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "title" => $this->title,
            "content" => $this->content,
            "tag" => $this->tag,
            "status" => $this->status,
            // "picture" => $this->picture,
            "comments" => CommentResource::collection($this->comments),
            "publisher" => new UserResource($this->user),
            "created_at" => $this->created_at,

        ];
    }
}
