<?php

namespace App\Http\Resources;

use App\Models\Admin;
use Illuminate\Http\Request;
use App\Http\Resources\AdminResource;
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
            'created_by'=> AdminResource::make($this->admin)
        ];
    }
}
