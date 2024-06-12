<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepositWithdrawRequest;

use App\Http\Resources\DepositWithdrawResource;
use App\Http\Resources\TransferResource;
use App\Http\Resources\UserResource;
use App\Models\DepositWithdraw;
use App\Models\Transfer;
use App\Services\DepositWithdrawService;
use App\Services\TransferService;
use App\Services\UserService;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    use HttpResponses;

    protected $transfer, $user, $depositWithdraw;

    public function __construct(UserService $user, TransferService $transfer, DepositWithdrawService $depositWithdraw)
    {
        $this->user = $user;
        $this->transfer = $transfer;
        $this->depositWithdraw = $depositWithdraw;

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
                return $this->depositOrWithDraw($transferRequest);
            case 'withdraw':
                return $this->depositOrWithDraw($transferRequest);
            case 'withdraw':           
                return $this->depositOrWithDraw($transferRequest);           
            case 'adminlist':
                return $this->adminlist($transferRequest);       

            default:
                return response()->json('This process is invalid', 400);

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
    
        // Check user accounts are deactivated or deleted
        // $this->checkStatus($senderAccountNo);
        // $this->checkStatus($receiverAccountNo);


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

    public function depositOrWithDraw($request){
        $validated = $request->validate([
            'process' => 'required',
            'data.accountNo' => 'required',
            'data.amount' => 'required',
        ]);


        $accountNo = $validated['data']['accountNo'];

        // check user account is deactivated of freezed
        // $this->checkStatus($accountNo);

        $amount = $validated['data']['amount'];
        $type = $validated['process'];
        $adminId = Auth::id();
        $date = Carbon::now()->toDateString();
       $time = Carbon::now()->toTimeString();

        $user = $this->user->getUserByAccountNo($accountNo); // get user account data
        if($user->isDeactivate){  // checking if the account is freeze or not
            return response()->json([
                'success' => false,
                'message' => "Account was freezed!"
            ]);
        }else if($user->isDelete){
            return response()->json([
                'success' => false,
                'message' => "Account was deleted!"
            ]);
        }else{
            $validated['adminId'] = Auth::user()->id;
            $currentBalance = $user->balance;

            if($type == 'deposit' ){
                    $totalBalance = $currentBalance+ $amount;
            }else{
                $totalBalance = $currentBalance- $amount;
            }

            $depositAcc = $this->user->balanceUpdateToAccount($totalBalance,$accountNo); // update blance to user account

            // prepare data for insert
            $insertData = [
                'process'=> $type,
                'accountNo'=> $accountNo,
                'amount'=> $amount,
                'adminId' => $adminId,
                'date'=> $date,
                'time'=> $time

            ];

            $deposit = $this->depositWithdraw->insert($insertData);

            if($depositAcc && $deposit){
                $resDeposit = DepositWithdrawResource::make($deposit);

                return $this->success($resDeposit, 'success',200);
            }
        }


    }



    public function adminlist($transferRequest)
    {
        if (isset($transferRequest->data['adminCode'])) {
            $validated = $transferRequest->validate([
                'data.adminCode'=> 'required'
            ]);
            $adminCode = $validated['data']['adminCode'];

            $admin = $this->admin->getAdminByAdminCode($adminCode);
            $adminId = $admin->id;
            $transfers = Transfer::where('adminId',$adminId)->get();



            $withdraws = DepositWithdraw::where('process', 'withdraw')
            ->orWhere('process', 'deposit')
            ->where('adminId',$adminId)
            ->get();



            // $transactions = $withdraws->merge($transfers);
              $transactions = collect(Arr::collapse([$transfers,$withdraws]));



            $orderTransactions = $transactions->sortByDesc('created_at')->values()->all();

        }else{
            $validated = $transferRequest->validate([
                'data.username'=> 'required'
            ]);

            $adminCode = $validated['data']['username'];
        }



        return response()->json([
            'transactions'=>$orderTransactions

        ]);

    }

    // public function checkStatus($accountNo)
    // {
    //     if ($this->user->checkDeactivate($accountNo) == 1 ) {
    //         return $this->error(null, "Cannot Transfer, The sender's account has been deactivated", 204);
    //      }
 
    //      if ($this->user->checkDeactivate($accountNo) == 1) {
    //          return $this->error(null, "Cannot Transfer, The receiver's account has been deactivated", 204);
    //       }
        
    //       if ($this->user->checkDelete($accountNo) == 1 ) {
    //         return $this->error(null, "Cannot Transfer, The sender's account has been freezed", 204);
    //      }
 
    //      if ($this->user->checkDelete($accountNo) == 1) {
    //          return $this->error(null, "Cannot Transfer, The receiver's account has been freezed", 204);
    //       }
        
 
    // }
}
