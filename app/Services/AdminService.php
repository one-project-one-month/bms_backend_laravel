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

    public function getUserPending($id)
    {
        return $this->connection()->query()->where('ProductCategoryId', $id)->first();
    }

    public function getAdminById($id){
        return $this->connection()->query()->where('id',$id)->first();
    }


}
