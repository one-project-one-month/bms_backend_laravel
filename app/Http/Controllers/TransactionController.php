<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferRequest;
use App\Http\Resources\TransferResource;
use App\Models\Transfer;
use App\Services\TransferService;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    use HttpResponses;

    protected $transfer;

    public function __construct(TransferService $transfer)
    {
        $this->transfer = $transfer;
    }


    public function createTransaction(TransferRequest $transferRequest)
    {
        $transferRequest->validate([
            'process'=> 'required'
        ]);
        if ($transferRequest->process === "transfer") {
           
            $validated = $transferRequest->validated();

            $validated['data']['adminId'] = Auth::id();
            $validated['data']['date'] = Carbon::now()->toDateString();
            $validated['data']['time'] = Carbon::now()->toTimeString();
             
             $insert = $this->transfer->insert($validated['data']);
            if ($insert) {
                $transfer = $this->transfer->getByTransferId($insert->id);
               
            }
            $res = TransferResource::make($transfer);
           return $this->success($res, 'success', 200);


        }
    }
}
