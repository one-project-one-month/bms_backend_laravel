<?php

namespace App\Services;

use App\Models\Transfer;

use App\Services\CommonService;


class TransferService extends CommonService
{
    public function connection(){
        return new Transfer();
    }

   

}
