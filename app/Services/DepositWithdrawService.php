<?php

namespace App\Services;

use App\Models\DepositWithdraw;
use App\Services\CommonService;


class DepositWithdrawService extends CommonService
{
    public function connection(){
        return new DepositWithdraw();
    }


    public function getTransactionByAccountNo($accountNo)
    {
        return $this->connection()->query()->where('accountNo',$accountNo)->first();

    }





}
