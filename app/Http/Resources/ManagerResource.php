<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ManagerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'adminCode'=> $this->adminCode,
            'name' => $this->name,
            'email' => $this->email,
            'role'=> $this->role
        ];
    }
}
