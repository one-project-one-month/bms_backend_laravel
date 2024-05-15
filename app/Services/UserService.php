<?php

namespace App\Services;

use App\Models\User;
use App\Services\CommonService;


class UserService extends CommonService
{
    public function connection(){
        return new User();
    }

    public function getUserById($id){
        // return $this->connection()->query()->where('id',$id)->first();
    }

    public function update(array $data,string $id){
        // return $this->connection()->query()->where('id',$id)->update($data);
    }



}
