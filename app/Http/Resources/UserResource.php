<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'status'=>$this->status,
            'username'=>$this->username,
            'phone_number'=>$this->phone_number,
            'avatar'=>$this->avatar,
            'identity_number'=>$this->identity_number,
            'date_start_working'=>$this->date_start_working,
            'organization' => new OrganizationResource($this->whenLoaded('organization')),
            'local_ward' => new WardResource($this->whenLoaded('belongward')),
            'roles' => RoleResource::collection($this->whenLoaded('roles')),
            'manager' =>new UserResource($this->whenLoaded('manager')),
            'managedUsers' =>new RoleResource($this->whenLoaded('managedUsers')),
        ];
    }
}
