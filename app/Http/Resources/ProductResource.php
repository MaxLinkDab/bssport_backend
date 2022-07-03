<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return[
            'id' => $this->id,
            'name' => $this->name,
            'description'=> $this->description,
            'photo'=> $this->photo,
            'price'=> $this->price,
            'price_kid' => $this->price_kid,
            'growth'=> $this->growth,
            'color'=> $this->color,
            'material'=> $this->material,
            'gender'=> $this->gender,
            'kid'=> $this->kid,
            // 'created_at' => $this->created_at, 
        ];
    }
}
