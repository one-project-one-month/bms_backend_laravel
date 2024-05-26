<?php

namespace App\Services;

use App\Models\User;
use App\Services\CommonService;


class UserService extends CommonService
{
    public function connection(){
        return new User();
    }

    public function getAllPendingUsers()
    {
        return $this->connection()->query()->where('status', 'pending')->get();
    }

    public function updateUserStatus( string $status ,string $accountNo){
        return $this->connection()->query()->where('accountNo',$accountNo)->update(['status' => $status]);
    }

    public function accountDeactivated(string $status,string $accounNo){
        return $this->connection()->query()->where('accountNo',$accounNo)->update(['isDeactivated' => $status]);
    }

    public function accountDelete(string $status,string $accounNo){
        return $this->connection()->query()->where('accountNo',$accounNo)->update(['isDelete'=> $status]);
    }

    public function getUserByAccountNo($accountNo)
    {
        return $this->connection()->query()->where('accountNo',$accountNo)->first();

    }

    public function getUserById($id){
        // return $this->connection()->query()->where('id',$id)->first();
    }

    public function update(string $status,string $accountNo){
        // return $this->connection()->query()->where('id',$id)->update($data);
    }



}
