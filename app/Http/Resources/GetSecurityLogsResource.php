<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetSecurityLogsResource extends JsonResource
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
            'severity' => $this->severity,
            'source' => $this->source,
            'ipAddress' => $this->ip_address,
            'userId' => $this->user_id,
            'event' => $this->event,
        ];
    }
}
