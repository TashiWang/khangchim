<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HouseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'area_id' => $this->area_id,
            'address' => $this->address,
            'contact' => $this->contact,
            'number_of_room' => $this->number_of_room,
            'number_of_toilet' => $this->number_of_toilet,
            'number_of_balcony' => $this->number_of_balcony,
            'rent' => $this->rent,
            'featured_image' => $this->featured_image,
            'images' => $this->images,
            'status' => $this->status,
            'user' => new UserResource($this->user),
            'area' => new AreaResource($this->area),
        ];
    }
}
