<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $images = [];
        // dd($this->images);
        // $m = array("1.jpg" , "2.jpg");
        // $m_s = json_encode($m);
        // dd($m_s);
        $brand = str_replace(' ', '_', $this->seller->name);
        foreach (json_decode($this->images) as $image){
            $images[] = [asset("uploads/items/$brand/$image")];
        }
        return [
            "name" =>$this->name,
            "images" => $images,
            "brand" => $this->seller->name,
            "price" => $this->NoItems,
            "desc" => $this->desc
        ];
    }
}
