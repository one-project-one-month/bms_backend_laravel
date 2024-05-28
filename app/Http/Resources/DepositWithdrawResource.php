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
            'TransactionType' => $this->transactionType,
            'AccountNo' => $this->accountNo,
            'Amount' => $this->amount,
            'TransactionDate' => $this->created_at,
            'AdminId' => $this->adminId
        ];
    }
}
