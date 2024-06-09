<?php

namespace App\Services;

use App\Models\Admin;
use App\Services\CommonService;

class AdminService extends CommonService
{
    public function connection()
    {
        return  new Admin();
    }

    // public function getAllAdminsWithoutTrash()
    // {
    //     return $this->connection()->query()->all();
    // }

    public function getAdminByEmail($email)
    {
        return $this->connection()->query()->withTrashed()->where('email', $email)->first();

    }


    public function getAdminByAdminCode($adminCode)
    {
        return $this->connection()->query()->withTrashed()->where('adminCode',$adminCode)->first();

    }

    public function checkDeactivated($adminCode, $status)
    {
        return $this->connection()->query()->withTrashed()->where('adminCode', $adminCode)->first();

    }

    public function getAdminByName($name)
    {
        return $this->connection()->query()->withTrashed()->where('name',$name)->first();

    }

    public function updateAccountStatus(bool $status,string $adminCode){
        return $this->connection()->query()->withTrashed()->where('adminCode',$adminCode)->update(['isDeactivate' => $status]);
    }

    public function updateAccountDelete(bool $status, string $adminCode)
    {
        return $this->connection()->query()->withTrashed()->where('adminCode',$adminCode)->update(['isDelete' => $status]);
    }


}
