<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferRequest;
use App\Models\Transfer;
use App\Services\TransferService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{

    protected $transfer;

    // function __construct(TransferService $transfer)
    // {
    //     $this->$transfer = $transfer;
    // }

    public function createTransaction(Request $transferRequest)
    {
        $transferRequest->validate([
            'process'=> 'required'
        ]);
        if ($transferRequest->process === "transfer") {
           
            $validated = $transferRequest->validate([
                'data.sender' => 'required|exists:users,accountNo',
                'data.receiver'=> 'required|exists:users,accountNo',
                'data.transferAmount'=> 'required'
            ]);

             //dd($validated);         
           
            $transfer = new Transfer();
            $transfer->sender = $validated['data']['sender'];
             $transfer->receiver = $validated['data']['receiver'];
             $transfer->transferAmount = $validated['data']['transferAmount'];
            $transfer->save();

           return $transfer;


        }
    }
}
