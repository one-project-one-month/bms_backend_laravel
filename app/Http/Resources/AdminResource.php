<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'Email' => $this->email,
            'UserName' => $this->userName,
            'DOB' => $this->DOB,
            'TownshipCode' => $this->townshipCode,
            'Role' => $this->role
        ];
    }
}
