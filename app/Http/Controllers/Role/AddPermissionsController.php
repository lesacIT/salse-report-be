<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AddPermissionsController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('permission:AddPermissions.usersHaveRoles')->only('usersHaveRoles');
    //     $this->middleware('permission:AddPermissions.postusersHaveRoles')->only('postusersHaveRoles');
    //     $this->middleware('permission:AddPermissions.deleteusersHaveRoles')->only('deleteusersHaveRoles');
    //     $this->middleware('permission:AddPermissions.RolesHavePermission')->only('RolesHavePermission');
    //     $this->middleware('permission:AddPermissions.postRolesHavePermission')->only('postRolesHavePermission');
    //     $this->middleware('permission:AddPermissions.deleteRolesHavePermission')->only('deleteRolesHavePermission');
    //     $this->middleware('permission:AddPermissions.RoleshaveUsers')->only('RoleshaveUsers');
        
    // }

    public function usersHaveRoles()
    {

        $users = User::all();

    
        $usersData = [];

    
        foreach ($users as $user) {
        
            $roles = $user->roles->pluck('id','name')->toArray();

            $usersData[] = [
                'id'=>$user->id,
                'name' => $user->name,
               'role_id' =>array_values($roles) , 
                'roles' =>  array_keys($roles),
            ];
        }

        return response()->json(['data' => $usersData], 200);
    }
    public function postusersHaveRoles(Request $request){
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_id' => 'required|exists:roles,id',
        ]);
        
        $user = User::find($validatedData['user_id']);
        $role = Role::where('id',$validatedData['role_id'])->first();
       
        $apiRole = Role::firstOrCreate([
            'name' => $role->name,
            'guard_name' => 'api',
        ]);
        $user->assignRole($apiRole);
       return response()->json(['massage'=>'Added permissions for users']);
        // $apiPermission = Permission::firstOrCreate(['name' => 'edit articles7', 'guard_name' => 'api']);
        // $apiRole->givePermissionTo($apiPermission);
        // $user->givePermissionTo('edit articles7', 'api');
    }
    public function deleteusersHaveRoles(Request $request)
{
    
    $validatedData = $request->validate([
        'user_id' => 'required|exists:users,id',
        'role_id' => 'required|exists:roles,id',
    ]);
    
        $user = User::find($validatedData['user_id']);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

     
        $role = Role::find($validatedData['role_id']);
        if (!$role) {
            return response()->json(['error' => 'Role not found'], 404);
        }

        $user->removeRole($role);

       
        return response()->json(['message' => 'Role revoked from user successfully'], 200);
}


    public function RolesHavePermission()
    {
        // Retrieve all roles with their permissions
        $roles = Role::with('permissions')->get();
    
        // Prepare data array
        $data = [];
    
        // Iterate through each role and format the data
        foreach ($roles as $role) {
            $roleData = [

                'role' => $role->name,
                'permissions' => $role->permissions->pluck('id','name')->toArray(),
            ];
            $data[] = $roleData;
        }
    
        // Return JSON response
        return response()->json([
            'roles' => $data,
        ]);
    }
    public function postRolesHavePermission(Request $request)
    {
        $validatedData=$request->validate([
            'role_id' => 'required|exists:roles,id',
            'permission_id' => 'required|exists:permissions,id',
        ]);
        $permission = Permission::find($validatedData['permission_id']);
        $role = Role::find($validatedData['role_id']);
        $role->givePermissionTo($permission);
        return response()->json(['message' => 'Permission assigned successfully'], 200);
    }
    public function deleteRolesHavePermission(Request $request)
    {
        
        $validatedData = $request->validate([
            'role_id' => 'required|exists:roles,id',
            'permission_id' => 'required|exists:permissions,id',
        ]);
        
            $role = Role::find($validatedData['role_id']);
            if (!$role) {
                return response()->json(['error' => 'User not found'], 404);
            }
    
         
            $permission = Permission::find($validatedData['permission_id']);
         
            if (!$permission) {
                return response()->json(['error' => 'Role not found'], 404);
            }
    
            $role->revokePermissionTo($permission);
    
           
            return response()->json(['message' => 'Permission revoked from user successfully'], 200);
    }

    public function RoleshaveUsers()
    {
        $roles = Role::whereHas('users')->get();

        return response()->json([
            'roles' => $roles,
        ]);
    } 
}

