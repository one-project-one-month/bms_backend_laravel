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

    public function getUserPending($id)
    {
        return $this->connection()->query()->where('ProductCategoryId', $id)->first();
    }

    public function getAdminByAdminCode($adminCode)
    {
        return $this->connection()->query()->withTrashed()->where('adminCode',$adminCode)->first();

    }

    public function getAdminByUsername($username)
    {
        return $this->connection()->query()->withTrashed()->where('username',$username)->first();

    }

    public function updateAccountStatus(bool $status,string $adminCode){
        return $this->connection()->query()->withTrashed()->where('adminCode',$adminCode)->update(['isDeactivate' => $status]);
    }


}
