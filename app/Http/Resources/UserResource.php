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
            'AccountNo' => $this->accountNo,
            'Username' => $this->username,
            'Email' => $this->email,
            'Phone' => $this->phone,
            'Balance' => $this->balance,
            'isDelete' => $this->isDelete,
            'isDeactivate' => $this->isDeactivate,
            'StateCode' => $this->stateCode,
            'TownshipCode' => $this->townshipCode,
        ];
    }
}
