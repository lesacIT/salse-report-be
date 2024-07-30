<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DailyReportsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        
            return [
                "id"=> $this->id,
                "date"=> $this->date,
                "period_time"=> $this->period_time,
                "app_crc"=> $this->app_crc,
                "loan_crc"=> $this->loan_crc,
                "app_plxs"=> $this->app_plxs,
                "loan_plxs"=> $this->loan_plxs,
                "amount_plxs"=> $this->amount_plxs,
                "amount_banca"=> $this->amount_banca,
                "loan_ctbs"=> $this->loan_ctbs,
                "conver_banca"=> $this->conver_banca,
                "conver_ctbs"=> $this->conver_ctbs,
                "belong"=> new UserResource($this->whenLoaded('belong')),
            ];
    }
}
