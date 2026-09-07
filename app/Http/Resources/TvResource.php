<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TvResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $activeSession = $this->playSessions?->where('status', 'active')->first();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'price_per_hour' => (int) $this->price_per_hour,
            'status' => $this->status,
            'is_buzzer_on' => (bool) $this->is_buzzer_on,
            'iot_endpoint' => $this->iot_endpoint,
            'active_session' => $activeSession ? new PlaySessionResource($activeSession) : null,
        ];
    }
}
