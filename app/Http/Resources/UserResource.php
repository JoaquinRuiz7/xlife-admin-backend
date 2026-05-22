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
            'cellphone' => $this->cellphone,
            'country' => $this->country->name,
            'registered' => $this->created_at,
            'lastActive' => $this->last_login_at?->diffForHumans(),
            'lastKnownIp' => $this->ip_address,
        ];
    }
}
