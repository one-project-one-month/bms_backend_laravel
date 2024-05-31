<?php

namespace App\Http\Controllers;


use App\Http\Requests\AdminRegisterRequest;
use App\Http\Resources\AdminResource;
use App\Models\Admin;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Traits\HttpResponses;
use App\Services\AdminService;
use App\Traits\GenerateCodeNumber;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    use GenerateCodeNumber, HttpResponses;
    protected $user, $admin;

    public function __construct(UserService $user, AdminService $admin)
    {
        $this->user = $user;
        $this->admin = $admin;
    }

    public function index()
    {
        $admins = Admin::all();
        return response()->json($admins);
    }


    public function insert(AdminRegisterRequest $request)
    {

        DB::beginTransaction();

        try {
           
            $data = $request->validated();

            $data['name'] = $request->name;
            $data['email'] = $request->email;
            $data['adminCode'] = $this->generateUniqueCode('Adm');
            $data['password'] = Hash::make($request->password);
            $data['role'] = $request->role;
            
            if($request->role !== 'admin')
            {
                $data['managerId'] = Auth::user()->id;
            }
    
             $adminSuccess = $this->admin->insert($data);
             $resAdmin = AdminResource::make($adminSuccess);

            DB::commit();
        } catch (\Throwable $th) {
            
            DB::rollBack();
            throw $th;
          
        }
  
        // $resAdmin = $this->admin->getAdminByAdminCode($data['adminCode']);

        return  $this->success($resAdmin, 'success', 200);
    }

    public function accountActions(Request $request)
    {
        
        $request->validate([
            'isDeactivate' => 'required|boolean',
            'adminCode' => 'required|string',
            'process' => 'required|in:deactivate,reactivate'
        ]);

        // Ensure the authenticated user is an admin
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Sorry, Employee role cannot make deactivation process'], 403);
        }

        // Get the necessary request data
        $status = $request->isDeactivate;
        $adminCode = $request->adminCode;
        $process = $request->process;

        // Find the admin, including trashed ones
        $admin = $this->admin->getAdminByAdminCode($adminCode);

        if (!$admin) {
            return response()->json(['message' => 'AdminCode cannot be found'], 404);
        }

        // Update the admin's status
         $this->admin->updateAccountStatus($status, $adminCode);
        $updatedAdmin = $this->admin->getAdminByAdminCode($adminCode);
      
        // Deactivate or reactivate based on the process
        if ($process === 'deactivate' && $status == 1) {
           
            $updatedAdmin->delete(); // Soft delete
            return $this->success($updatedAdmin, "Account is Deactivated", 200);
        
        } elseif ($process === 'activate' && $status == 0) {
       
            $updatedAdmin->restore(); // Restore
            return $this->success($updatedAdmin, "Account is reactivated", 200);
       
        } else {
      
            return $this->error(null, 'Invalid process or status', 403);   
        }
    }




    public function getAllPendingUsers(){
       $users = $this->user->getAllPendingUsers();
        return response()->json($users);
    }

    public function userAcceptOrReject(Request $request, $accountNo){


        // Think about whether admins can update other information except status

        // ** Admin can update only status **
       $userUpdate = $this->user->updateUserStatus($request->status, $accountNo );

        if ($userUpdate) {

            $user = $this->user->getUserByAccountNo($accountNo);

            return response()->json($user);
        }



    }
}
