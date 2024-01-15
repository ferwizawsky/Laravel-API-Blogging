<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JadwalUjianResource extends JsonResource
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
            "author" => [
                "username" =>  $this->user?->username,
                "name" => $this->user?->name,
            ],
            "kelas" => [
                "id" => $this->kelas?->id,
                "title" => $this->kelas?->title,
            ],
            "title" => $this->title,
            "day" => $this->day,
            "created_at" => $this->created_at
        ];
    }
}
