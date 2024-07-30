<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrganizationResource;
use App\Models\OrganizationModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
       /** OrganizationController
     * Display a listing of the resource.
     */
    public function __construct()
    {
//   $this->middleware('permission:Organization.index')->only('index');
//   $this->middleware('permission:Organization.store')->only('store');
//   $this->middleware('permission:Organization.show')->only('show');
//   $this->middleware('permission:Organization.edit')->only('edit');
//   $this->middleware('permission:Organization.update')->only('update');
//   $this->middleware('permission:Organization.delete')->only('destroy');
    }
    public function index()
    {
        $organizations =OrganizationModel::get();
       
        // $organizations->load('manager');
        // $organizations->load('managedOrganization');
        
        return response()->json(['data' => OrganizationResource::collection( $organizations)],200);
    }
    public function GetFreeOrganization()
    { 
        $user = Auth::user();
      
        $organizations =OrganizationModel::all();
    
        $tree = $this->buildTree($organizations, $user['organization_id'] - 1);
        
        $treeData = $this->displayTree($tree);
        return response()->json(['tree' => $treeData]);
    }
    
    protected function buildTree($organizations, $parentId)
    {
        $tree = [];
    
        foreach ($organizations as $organization) {
            if ($organization['parent_id'] == $parentId) {
                $organization['children'] = $this->buildTree($organizations, $organization['id']);
                $tree[] = $organization;
            }
        }    
        return $tree;
    }
    
    protected function displayTree($tree, $level = 0)
    {
        $result = [];
    
        foreach ($tree as $node) {
            $result[] = [
                'id' => $node['id'],
                'name' => $node['name'],
                'children' => $this->displayTree($node['children'], $level + 1),
            ];
        }
    
        return $result;
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
    $user = Auth::user();

    $organization = $user->organization;
    
    $level = OrganizationModel::where('id', $organization->id)->value('level');
    
    if ($level > $request->input('level')) {
        return response()->json(['message' => 'Cannot create an organization with a higher level than the current organization.'], 403);
    }

    $request->validate([
        'name' => 'required|string',
        'code' => 'required|string',
        'level' => 'required|integer',
        'parent' => 'required|integer',
    ]);

    $organizationModel = OrganizationModel::create([
        'name' => $request->input('name'),
        'code' => $request->input('code'),
        'level' => $request->input('level'),
        'parent_id' =>  $request->input('parent'),
    ]);
    
    return response()->json(['organizationModel' =>  OrganizationResource::collection( $organizationModel)]);
}
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
      
        $organization = OrganizationModel::find($id);
    
        if (!$organization) {
            return response()->json(['message' => 'Organization not found.'], 404);
        }
    
        $organizationResource = new OrganizationResource($organization);
    
        return response()->json(['organization' => $organizationResource]);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $organization = OrganizationModel::find($id);
    
        if (!$organization) {
            return response()->json(['message' => 'Organization not found.'], 404);
        }
    
        $organizationResource = new OrganizationResource($organization);
    
        return response()->json(['organization' => $organizationResource]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();

        $organization = $user->organization;
        
        $level = OrganizationModel::where('id', $organization->id)->value('level');
        
        if ($level > $request->input('level')) {
            return response()->json(['message' => 'Cannot create an organization with a higher level than the current organization.'], 403);
        }
        $Organization = OrganizationModel::find($id);
        $Organization->update($request->all());
        $organizationResource = new OrganizationResource($Organization);
        return response()->json(['organizationModel' => $organizationResource]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $Organization = OrganizationModel::find($id);

        if ($Organization) {
            $deleted = $Organization->delete();

            if ($deleted) {
                $organizations =OrganizationModel::get();
                return response()->json([
                    'message' => 'Organization deleted successfully',
                    'data' => OrganizationResource::collection( $organizations)
                ], 200);
            } else {
                return response()->json(['message' => 'Failed to delete organization'], 500);
            }
        } else {
            return response()->json(['message' => 'Organization not found'], 404);
        }
    }
    public function UserOfOrganization(){
        
        $organizations =OrganizationModel::get();
       
        $organizations->load('have');
       
       $data=  OrganizationResource::collection( $organizations);
     //  dd( $data);
        return response()->json(['data' =>  $data],200);
    }
}
