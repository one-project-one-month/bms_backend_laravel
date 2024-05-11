<?php
namespace App\Services;



abstract class CommonService
{
    public function insert(array $data)
    {
        return $this->connection()->query()->create($data);
    }

    public function getDataById($id)
    {
        return $this->connection()->query()->where('', $id)->first();
    }


    abstract public function connection();
}
