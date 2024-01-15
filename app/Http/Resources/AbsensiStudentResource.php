<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AbsensiStudentResource extends JsonResource
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
            "user" => [
                "username" =>  $this->user?->username,
                "name" => $this->user?->name,
            ],
            "kelas" => [
                "id" => $this->kelas?->id,
                "title" => $this->kelas?->title,
                "author" =>
                [
                    "username" =>  $this->user?->username,
                    "name" => $this->user?->name,
                ],
            ],
            "min" => $this->kelas?->min,
            "status" => $this->kelas?->min >  $this->total ? "Belum Cukup" : "Cukup",
            "total" => $this->total,
            "created_at" => $this->created_at
        ];
    }
}
