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
            'TransactionType' => $this->transactionType,
            'AccountNo' => $this->accountNo,
            'Amount' => $this->amount,
            'TransactionDate' => $this->created_at,
            'AdminId' => new AdminResource(Admin::findOrFail($this->adminId))
        ];
    }
}
