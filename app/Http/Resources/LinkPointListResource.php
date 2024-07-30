<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LinkPointListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string=>$this->date, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
                'date'=>$this->date,
                'address_dlk'=>$this->date,
                'full_name_of_representative'=>$this->date,
                'image'=>$this->image,
                'locate'=>$this->locate,
                'status_dlk'=>$this->status_dlk,
                'advise_crc'=>$this->advise_crc,
                'eligible_crc'=>$this->eligible_crc,
                'go_to_app_crc'=>$this->go_to_app_crc,
                'loan_crc'=>$this->loan_crc,
                'ward' => new WardResource($this->whenLoaded('belongward')),
                'all_parents' =>$this->all_parents,
                
];
    }
}
