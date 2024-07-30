<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DistrictResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'district_name' => $this->district_name,
            'province' => new ProvinceResource($this->whenLoaded('province')),
            'wards' => WardResource::collection($this->whenLoaded('wards')),
        ];
    }
}
