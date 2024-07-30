<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequests;
use App\Http\Resources\RoleResource;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    

    public function __construct()
    {
        //  $this->middleware('check:Role');
        // $this->middleware('permission:Role.index')->only('index');
        // $this->middleware('permission:Role.store')->only('store');
        // $this->middleware('permission:Role.show')->only('show');
        // $this->middleware('permission:Role.edit')->only('edit');
        // $this->middleware('permission:Role.update')->only('update');
        // $this->middleware('permission:Role.delete')->only('destroy');
    }
    public function index()
    {
        // $apiPermission = Permission::firstOrCreate(['name' => 'edit articles7', 'guard_name' => 'api']);
        // $apiRole = Role::firstOrCreate(['name' => 'ADMIN7', 'guard_name' => 'api']);
        // $apiRole->givePermissionTo($apiPermission);
        $role = Role::get();
        $role->load('permissions');
        $group =  RoleResource::collection($role);
        return response()->json([
            'data' => $group,
        ]);
    }

    public function store(RoleRequests $request)
    {

        $result = Role::create($request->only('name', 'guard_name',));

        if ($result) {
            $group = new  RoleResource($result);
            return response()->json([
                'message' => 'Success', 'data' => $group
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failure',
            ], 400);
        }
    }

    public function show($role)
    {
        $role = Role::find($role);
        $group = new  RoleResource($role);
        if ($role) {
            return response()->json([
                'message' => $group,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failure',
            ], 400);
        }
    }
    // public function edit($role)
    // {
    //     $role = Role::where('id', $role)->first();
    //     if ($role) {
    //         return response()->json([
    //             'message' => $role,
    //         ], 200);
    //     } else {
    //         return response()->json([
    //             'message' => 'Failure',
    //         ], 400);
    //     }
    // }


    public function update(RoleRequests $request, $role)
    {
        $role = Role::find($role);

        $role->update($request->only('name', 'guard_name'));

        $group = new  RoleResource($role);

        return response()->json(['message' => 'Role updated successfully', 'data' => $group], 200);
    }

    public function destroy($role)
    {
        $role = Role::find($role);

        if ($role) {
            $role->delete();
            return response()->json(['message' => 'Role deleted successfully'], 200);
        }

        return response()->json(['message' => 'Role does not exist'], 404);
    }
}
