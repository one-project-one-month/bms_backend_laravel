<?php

namespace App\Http\Resources;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class AdminResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {



        if (is_null($this->managerId)) {
           $manager = null;
        }else{
            $manager = Admin::findOrFail($this->managerId);
        }
       

        return [
            'adminCode'=> $this->adminCode,
            'name' => $this->name,
            'email' => $this->email,
            'role'=> $this->role,
            $this->mergeWhen($request->process == "deactivate" || 
            $request->process == "activate", [
                'isDelete' => $this->isDelete,
                'isDeactivate'=> $this->isDeactivate,
                'created-by' => ManagerResource::make($manager)
            ]),
            //when making transfer , removed this column from response

            'created_by' => $this->when(!isset($request->process) || $request->process == "search", ManagerResource::make(Auth::user()) )

        ];
    }
}
