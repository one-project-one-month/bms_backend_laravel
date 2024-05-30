<?php

namespace App\Http\Resources;

use App\Models\Admin;
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
        $manager = Admin::where('id', $this->managerId)->first();

        return [
            'name' => $this->name,
            'email' => $this->email,
            'role'=> $this->role,
            'created_by' => ManagerResource::make($manager)
            
            // 'created_by'=> AdminResource::make($this->managerId)
        ];
    }
}
