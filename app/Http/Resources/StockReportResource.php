<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class StockReportResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'symbol' => $this['symbol'],
            'name' => $this['name'],
            'price' => $this['price'],
            'percentage_change' => $this['percentage_change'],
            'timestamp' => $this['timestamp'],
        ];
    }
}
