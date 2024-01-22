<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $list =  parent::toArray($request);
        // $list['component'] = $this->component;
        $list['user'] = new UserResource($this->user);
        return $list;
    }

    // return [
    //     "name" => $this->name,
    //     "detail" => $this->detail,
    //     "price" => $this->price,
    //     "created_at" => $this->created_at
    // ];
}
