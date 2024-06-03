<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DepositWithdrawResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'DepositWithdrawId' => $this->id,
            'process' => $this->process,
            'accountNo' => $this->accountNo,
            'amount' => $this->amount,
            'transactionDate' => $this->created_at,
            'user' => UserResource::make($this->user),
            'created_by'=> AdminResource::make($this->admin)
        ];
    }
}
