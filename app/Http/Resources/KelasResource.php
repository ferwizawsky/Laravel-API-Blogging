<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KelasResource extends JsonResource
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
            "day" => $this->day,
            "isUnactive" => $this->isUnactive,
            "min" => $this->min,
            "author" => [
                "username" =>  $this->author?->username,
                "name" => $this->author?->name,
                "role_id" => $this->author?->role_id,
            ],
            'students' => StudentResource::collection($this->students),
        ];
    }
}
