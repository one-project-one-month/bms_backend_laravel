<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'sender'=> $this->sender,
            'receiver'=> $this->receiver,
            'transferAmount'=> $this->transferAmount,
            'date'=> $this->date,
            'time' => $this->time,
            'created_by'=> new AdminResource($this->admin) 

        ];
    }
}
