<?php

namespace App\Services;

use App\Models\Transfer;
use App\Services\CommonService;


class TransferService extends CommonService
{
    public function connection(){
        return new Transfer();
    }

    public function getByTransferId($id){
        return $this->connection()->query()->where('id',$id)->with('admin')->first();

    }

    public function getTransfer(string $columnName, string $column)
    {
        return $this->connection()->query()->with('admin')->where($columnName,$column)->get();

    }







}
