<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
use App\Traits\HttpResponses;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use App\Services\DepositWithdrawService;
use App\Http\Requests\DepositWithdrawRequest;
use App\Http\Resources\DepositWithdrawResource;

class DepositWithdrawController extends Controller
{
    use HttpResponses;
    protected $user, $depositWithdraw;

    public function __construct(UserService $user,DepositWithdrawService $depositWithdraw){
        $this->user = $user;
        $this->depositWithdraw = $depositWithdraw;
    }

    // deposit money to user account
    // admin and staff can only make this transaction
    public function deposit(DepositWithdrawRequest $request){
        $data = $request->validated();
        $accountNo = $request->accountNo;
        $depositAmount = $request->amount;
        $type = $request->transactionType;

        $user = $this->user->getUserByAccountNo($accountNo); // get user account data
        if($user->isDeactivate && $user->isDelete){  // checking if the account is freeze or not
            return response()->json([
                'success' => false,
                'message' => "Account was freeze!"
            ]);
        }else{
            if($type == 'deposit'){
                $data['adminId'] = Auth::user()->id;

                $currentBalance = $user->balance;

                $totalBalance = $currentBalance+ $depositAmount;

                $depositAcc = $this->user->balanceUpdateToAccount($totalBalance,$accountNo); // update blance to user account

                $deposit = $this->depositWithdraw->insert($data);

                if($depositAcc && $deposit){
                    $user = $this->user->getUserByAccountNo($accountNo);
                    $resUser = UserResource::make($user);
                    $resDeposit = DepositWithdrawResource::make($deposit);

                    return response()->json([
                        'deposit' => $resDeposit,
                        'userAccount' => $resUser,
                        'message' => 'success'
                    ],200);
                }

            }
        }


    }


    public function withdraw(DepositWithdrawRequest $request){
        $data = $request->validated();
        $data['adminId'] = Auth::user()->id;
        $accountNo = $request->accountNo;
        $type = $request->transactionType;
        $withdrawAmount = $request->amount;

        $user = $this->user->getUserByAccountNo($accountNo);

        if($user->isDeactivate && $user->isDelete){
            return response()->json([
                'success' => false,
                'message' => "Account was freeze!"
            ]);
        }else{
            $currentBalance = $user->balance;
            if($withdrawAmount > $currentBalance){
                return response()->json([
                    'success' => false,
                    'message' => "Not enough balance to withdraw"
                ]);
            }else{
                $finalBalance = $currentBalance - $withdrawAmount;
                $withdrawAcc = $this->user->balanceUpdateToAccount($finalBalance,$accountNo);

                $withdraw = $this->depositWithdraw->insert($data);

                if($withdrawAcc && $withdraw){
                    $user = $this->user->getUserByAccountNo($accountNo);
                    $resUser = UserResource::make($user);

                    $resWithdraw = DepositWithdrawResource::make($withdraw);

                    return response()->json([
                        'withdraw' => $resWithdraw,
                        'user' => $resUser,
                        'message' => 'success'
                    ]);
                }
            }
        }


    }
}
