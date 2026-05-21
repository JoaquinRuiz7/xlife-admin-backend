<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
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
            'report' => $this->report,
            'status' => $this->status,
            'reporter' => $this->reporter->name,
            'reported' => $this->reportedUser->name,
            'priority' => $this->priority,
            'moderatorNotes' => $this->moderator_notes,
        ];
    }
}
