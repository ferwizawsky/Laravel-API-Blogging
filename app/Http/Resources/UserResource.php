<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return [
            "id" => 24,
            "name" =>  $this->name,
            "username" =>  $this->username,
            "email" =>  $this->email,
            "role_id" => $this->role_id,
            "role" => $this->role_id ? "Admin" : "User",
            // "email_verified_at"=> null,
            "created_at" => $this->created_at,
        ];
    }
}
