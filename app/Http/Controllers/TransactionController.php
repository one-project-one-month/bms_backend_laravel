<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferRequest;
use App\Http\Resources\TransferResource;
use App\Models\Transfer;
use App\Services\TransferService;
use App\Services\UserService;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    use HttpResponses;

    protected $transfer, $user;

    public function __construct(UserService $user, TransferService $transfer)
    {
        $this->user = $user;
        $this->transfer = $transfer;

    }


    public function createTransaction(Request $transferRequest)
    {
        $transferRequest->validate([
            'process'=> 'required'
        ]);
        $process = $transferRequest->process;
        
        switch ($process) {
            case 'transfer':
               return $this->transfer($transferRequest);
            case 'deposit':
                    # code...
                 break;
            case 'withdraw':
           
                break;
            
            case 'list':
                return $this->list($transferRequest);       
            default:
                # code...
                break;
        }

    }


    public function transfer($transferRequest)
    {
        
      $validated =  $transferRequest->validate([
            'data.sender' => 'required|exists:users,accountNo',
            'data.receiver'=> 'required|exists:users,accountNo',
            'data.transferAmount'=> 'required'
        ]);

        $validated['data']['adminId'] = Auth::id();
        $validated['data']['date'] = Carbon::now()->toDateString();
        $validated['data']['time'] = Carbon::now()->toTimeString();

        $senderAccountNo = $validated['data']['sender'];
        $receiverAccountNo = $validated['data']['receiver'];

        $senderAccount = $this->user->getUserByAccountNo($validated['data']['sender']);
        $receiverAccount = $this->user->getUserByAccountNo($validated['data']['receiver']);

        $transferAmount = $validated['data']['transferAmount'];

        $senderBalance = $senderAccount->balance - $transferAmount;
        $receiverBalance = $receiverAccount->balance + $transferAmount;

        if ($senderBalance < $transferAmount) {
           return response()->json(['message'=> 'Cannot transfer. The balance of the sender is not enough']);
        }

        $this->user->balanceUpdateToAccount($senderBalance, $senderAccountNo);
        $this->user->balanceUpdateToAccount($receiverBalance, $receiverAccountNo);

         $insert = $this->transfer->insert($validated['data']);
        if ($insert) {
            $transfer = $this->transfer->getByTransferId($insert->id);          
        }
        $res = TransferResource::make($transfer);
       return $this->success($res, 'success', 200);
    }

    public function deposit()
    {

    }

    public function withdraw()
    {

    }

    public function list($transferRequest)
    {
        if (isset($transferRequest->data['adminCode'])) {
            $validated = $transferRequest->validate([
                'data.adminCode'=> 'required'
            ]);
            $keyword = $validated['data']['adminCode'];

            $transfers = Transfer::whereHas('admin', function ($query) use ($keyword){
                $query->where('adminCode',$keyword);
            })->get();
        }else{
            $validated = $transferRequest->validate([
                'data.username'=> 'required'
            ]);

            $keyword = $validated['data']['username'];
        }

       

        // $transfers = $this->transfer->getTransfer('adminCode', $keyword);

        return response()->json($transfers);
       
    }
}
