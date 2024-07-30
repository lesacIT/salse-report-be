<?php

namespace App\Http\Resources;

use App\Models\OrganizationModel;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
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
            'name' => $this->name,
            'level'=>$this->level,
            'code'=>$this->	code,
            'have'=>   UserResource::collection($this->whenLoaded('have')),
            'managedOrganization'=> new  OrganizationResource($this->whenLoaded('managedOrganization')),
            'manager'=> OrganizationResource::collection($this->whenLoaded('manager')),
         ];
         
    }
}
