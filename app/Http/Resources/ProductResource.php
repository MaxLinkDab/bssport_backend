<?php

namespace App\Http\Resources;

use App\Models\Color;
use App\Models\Media;
use App\Models\Product;
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
            'photo'=> MediaResource::collection($this->media),
            'price_and_size'=> SizeAndPriceResource::collection($this->Size_and_price),
            'sale' => $this->sale,
            'color'=> /* ColorResource::collection($this->color) ?? */ Color::where('product_id', $this->color)->get(['color']),
            'material'=> $this->material,
            'gender'=> $this->gender,
            // 'created_at' => $this->created_at, 
        ];
    }
}
