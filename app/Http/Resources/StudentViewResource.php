<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentViewResource extends JsonResource
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
            "min" => $this->min,
            "day" => $this->day,
            "isUnactive" => $this->isUnactive,
            "author" => [
                "username" =>  $this->author?->username,
                "name" => $this->author?->name,
                "role_id" => $this->author?->role_id,
            ],
        ];
    }
}
