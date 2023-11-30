<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
            "description" => $this->description,
            "time" => $this->time,
            "location" => $this->location,
            "slot" => $this->slot,
            "booking" => count($this->booking),
            "booking_user" => BookingUserResource::collection($this->booking),
            "user" => new UserResource($this->user),
            "created_at" => $this->created_at,
        ];
    }
}
