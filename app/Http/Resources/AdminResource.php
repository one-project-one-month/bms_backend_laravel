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


        // return parent::toArray($request);

        // $manager = Admin::findOrFail($this->managerId);

        return [
            'adminCode'=> $this->adminCode,
            'name' => $this->name,
            'email' => $this->email,
            'role'=> $this->role,
            //when making transfer , removed this column from response
            'created_by' => $this->when(!isset($request->process), ManagerResource::make(Auth::user())),

            // 'crated_by' => $this->when(!isset($request->balance), ManagerResource::make(Auth::user()))

            // 'created_by'=> AdminResource::make($this->managerId)
        ];
    }
}
