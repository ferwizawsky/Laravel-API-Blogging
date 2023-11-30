<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return
            [
                "id" => $this->id,
                "event" => new EventResource($this->event),
                "user_id" => $this->user_id,
                "created_at" => $this->created_at,
            ];
    }
}
