<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccountActionRequest;
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
         $resAdmins = AdminResource::collection($admins);
        return $this->success($resAdmins);
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

    public function accountActions(AccountActionRequest $request)
    {
       
         // Ensure the authenticated user is an admin
         if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Sorry, Employee role cannot make this process'], 403);
        }

        $validated = $request->validated();

       $adminCode = $validated['data']['adminCode'];
       $process = $validated['process'];    

        switch ($process) {
            case "search":
                return $this->search($adminCode);
            case 'activate':
                return $this->deactivateOrActivate($adminCode, $process);   
            case 'deactivate':
                return $this->deactivateOrActivate($adminCode, $process);
            case 'delete':
                return $this->accountDelete($adminCode, $process);
            default:
                return response()->json('This process is invalid', 400);
        }
    }

    public function update(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
        return response()->json(['message' => 'Sorry, Employee role cannot make update'], 403);
        }


        $admin = $this->admin->getAdminByAdminCode($request->adminCode);

       if (is_null($admin)) {
        $this->error(null, "Admin not found", 404);
       }

       if ($request->has('data.name')) {
        $admin->name = $request['data']['name'];
       }

       if ($request->has('data.email')) {
        $admin->name = $request['data']['email'];
       }

       if ($request->has('data.role')) {
        $admin->name = $request['data']['role'];
       }

          $admin->update();
   
        return $this->success(null, "Successfully updated", 200);

       

               
    }

    public function search($adminCode)
    {
       
        // Find the admin, including trashed ones
        $admin = $this->admin->getAdminByAdminCode($adminCode);
        $resAdmin = AdminResource::make($admin);
        return $this->success($resAdmin, "success", 200);

    }

    public function accountDelete($adminCode, $process){
      
        $status =  1;
       
        $admin = $this->admin->getAdminByAdminCode($adminCode);

        if ($admin == null) {
            return response()->json(['message' => 'AdminCode cannot be found'], 404);
        }

        if ($admin->isDeactivate === $status) {
            return response()->json([
                'message' => 'The account is already freezed'
            ]);
        }

        $accountDelete = $this->admin->updateAccountDelete($status, $adminCode);

    

        if($accountDelete){
            $admin = $this->admin->getAdminByAdminCode($adminCode);
            return $this->success($admin, "Account has bee freezed", 200);     
        }

    }

    public function deactivateOrActivate($adminCode, $process)
    {
       
        $process == "deactivate" ? $status = 1 : $status = 0 ;

         // Find the admin, including trashed ones
         $admin = $this->admin->getAdminByAdminCode($adminCode);

         if ($admin == null) {
            return response()->json(['message' => 'AdminCode cannot be found'], 404);
        }
     
        if ($admin->isDeactivate === $status) {
            return response()->json([
                'message' => $status? 'The account is already Deactivated': 'The account is already activated'
            ]);
        }

            // Update the admin's status
            $this->admin->updateAccountStatus($status, $adminCode);
            $updatedAdmin = $this->admin->getAdminByAdminCode($adminCode);

         // Deactivate or reactivate based on the process
         if ($process === 'deactivate' && $status == 1) {
           
            // $updatedAdmin->delete(); // Soft delete
            return $this->success(AdminResource::make($updatedAdmin), "Account is Deactivated", 200);
        
        } elseif ($process === 'activate' && $status == 0) {
       
            // $updatedAdmin->restore(); // Restore
            return $this->success(AdminResource::make($updatedAdmin), "Account is reactivated", 200);     
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
