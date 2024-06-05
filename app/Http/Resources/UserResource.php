<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\AdminResource;
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
            'userCode' => $this->userCode,
            'AccountNo' => $this->accountNo,
            'FullName' => $this->fullName,
            'Username' => $this->username,
            'Email' => $this->email,
            'Phone' => $this->phone,
            'Balance' => $this->balance,
            'isDelete' => $this->isDelete,
            'isDeactivate' => $this->isDeactivate,
            'StateCode' => $this->stateCode,
            'TownshipCode' => $this->townshipCode,
            'CreatedBy' => AdminResource::make($this->admin)
        ];
    }
}
