<?php

namespace App\Http\Resources;

use App\Models\item;
use Illuminate\Http\Resources\Json\JsonResource;

class BrandResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        $n_products = item::join('sellers', 'items.seller_id', '=', 'sellers.id')
            ->select('sellers.name', item::raw('COUNT(items.id) as product_count'))
            ->groupBy('sellers.name')
            ->get();
        $productCounts = $n_products->pluck('product_count')->first();


        return [
            "name" => $this->name,
            "phone"=>$this->phone,
            "email"=>$this->email,
            "des" => $this->desc,
            "n.products" => $productCounts,
            "rate" => $this->rate,
            "img" => asset("images/$this->img"),
            "backgroundImage" => asset("images/$this->backgroundImage"),
        ];
    }
}
