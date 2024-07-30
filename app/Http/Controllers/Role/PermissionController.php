<?php

namespace App\Http\Controllers\Role;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    // public function __construct()
    // {
    // $this->middleware('permission:Permission.index')->only('index');
    // $this->middleware('permission:Permission.store')->only('store');
    // $this->middleware('permission:Permission.show')->only('show');
    // $this->middleware('permission:Permission.edit')->only('edit');
    // $this->middleware('permission:Permission.update')->only('update');
    // $this->middleware('permission:Permission.delete')->only('destroy');
    // }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $permission=  Permission::all();
        return response()->json(['data'=> $permission]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate(['name'=>'required|string','guard_name'=>'required']);
        $result = Permission::create($request->only('name','guard_name'));
        if ($result) {
            return response()->json([
                'message' => 'Success','vitri'=>$result
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failure',
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $id = Permission::where('id', $id)->first();
        if ($id) {
            return response()->json([
                'message' => $id,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failure',
            ], 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $id = Permission::where('id', $id)->first();
        if ($id) {
            return response()->json([
                'message' => $id,
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failure',
            ], 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //toi day
        $permission = Permission::where('id', $id)->first();
    
        if (!$permission) {
            return response()->json(['message' => 'Role not found'], 404);
        }
    
        // Validate the request data
        $validatedData = [];
    
        if ($request->has('name')) {
            $validatedData['name'] = $request->validate([
                'name' => 'required|string|unique:roles,name',
            ]);
        }
    
        if ($request->has('guard_name')) {
            $validatedData['guard_name'] = $request->validate([
                'guard_name' => 'required|string',
            ]);
        }
    
        // Update role 
        $permission->update($validatedData);
    
        // Return message
        return response()->json(['message' => 'Role updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $permission = Permission::where('id', $id)->first();
    
        if ($permission == null) {
            return response()->json(['message' => 'Role does not exist'], 404);
        }
    
        $permission->delete();
    
        return response()->json(['message' => 'Role deleted successfully'], 200); 
    }
}
