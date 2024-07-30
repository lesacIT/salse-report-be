<?php

namespace App\Http\Controllers\User;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrganizationResource;
use App\Http\Resources\RoleResource;
use App\Http\Resources\UserResource;
use App\Models\OrganizationModel;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use OwenIt\Auditing\Models\Audit;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // spublic function __construct()
    //     {
    //   $this->middleware('permission:User.index')->only('index');
    //   $this->middleware('permission:User.store')->only('store');
    //   $this->middleware('permission:User.show')->only('show');
    //   $this->middleware('permission:User.edit')->only('edit');
    //   $this->middleware('permission:User.update')->only('update');
    //   $this->middleware('permission:User.delete')->only('destroy');
    //     }
    /**
     * Display a listing of the resource.
     */

     protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);

        $users = User::paginate($perPage);
        $users->load('organization');
        $users->load('belongward.district.province');
        $users->load('roles');
       // $tree = $this->buildTree($users);
       return response()->json([ 'data' => $users]);
    }

    protected function buildTree($users, $managerId = null)
    {
        $tree = [];

        foreach ($users as $user) {
            if ($user->manager_id == $managerId) {
                $user->children = $this->buildTree($users, $user->id);
                $tree[] = $user;
            }
        }

        return $tree;
    }


    /**
     * Show the form for creating a new resource.
     */
    public function createUser()
    {

        //
      $organization=  OrganizationModel::get();
      $role=   Role::get();
      $user=  User::get();
      return response()->json([
        'organization'=> OrganizationResource::collection($organization),
        'roles'=> RoleResource::collection($role),
        'user'=>UserResource::collection($user)],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $validatedData = $request->validate([
            'manager_id' => 'required|integer',
            'organization_id' => 'required|integer',
            'role_id' => 'required|integer',
            'username' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone_number' => 'required|string|max:15',
            'identity_number' => 'required|string|max:20',
            'avatar' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'id_local_province' => 'required|integer',
            'id_local_district' => 'required|integer',
            'id_local_ward' => 'required|integer',
            'path' => 'nullable|string',
            'api_token' => 'nullable|string',
            'email_verified_at' => 'nullable|date',
            'date_start_working' => 'required|date',
            'date_of_birth' => 'required|date',
        ]);

        $userData = [
            'manager_id' => $validatedData['manager_id'],
            'organization_id' => $validatedData['organization_id'],
            'role_id' => $validatedData['role_id'],
            'status' => 1,
            'username' => $validatedData['username'],
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'phone_number' => $validatedData['phone_number'],
            'identity_number' => $validatedData['identity_number'],
            'id_local_province' => $validatedData['id_local_province'],
            'id_local_district' => $validatedData['id_local_district'],
            'id_local_ward' => $validatedData['id_local_ward'],
            'path' => 1,
            'api_token' => null,
            'email_verified_at' => null,
            'date_start_working' => $validatedData['date_start_working'],
            'date_start_working' => $validatedData['date_of_birth'],
        ];
         if ($request->hasFile('avatar')) {

            $avatarPath = $request->file('avatar');


            $imageName = uniqid().'.'.$avatarPath->getClientOriginalExtension();


            $directory = 'public/images/avatars';
            $storedImagePath = $avatarPath->storeAs($directory, $imageName);

            $userData['avatar'] = 'images/avatars/'. $imageName;
        }
        $user = User::create($userData);


        return response()->json(['message' => 'User created successfully.', 'user' => $user]);
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $user = $this->userRepository->getUserById($id);
        $user->load('belongward.district.province');
        return response()->json(['user'=> $user],200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
      //  $user = User::with('organization','role','localProvince','localDistrict','localWard',)->find($id);
        $user = $this->userRepository->getUserById($id);
        return response()->json(['user'=>$user]);
    }

    /**
     * Update the specified resource in storage.
     */

     public function update(Request $request, $id)
     {

         $validatedData = $request->validate([
             'manager_id' => 'required|integer',
             'organization_id' => 'required|integer',
             'username' => 'required|string|max:255',
             'name' => 'required|string|max:255',
             'email' => 'required|string|email|max:255|unique:users,email',
             'phone_number' => 'required|string|max:15',
             'identity_number' => 'required|string|max:20',
             'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // check image
             'id_local_province' => 'required|integer',
             'id_local_district' => 'required|integer',
             'id_local_ward' => 'required|integer',
             'path' => 'nullable|string',
             'api_token' => 'nullable|string',
             'email_verified_at' => 'nullable|date',
             'date_start_working' => 'nullable|date',
         ]);


         $user = User::findOrFail($id);


         if ($request->hasFile('avatar')) {

             if ($user->avatar) {
                 Storage::delete('public/'.$user->avatar);
             }


             $avatarPath = $request->file('avatar');
             $imageName = uniqid() . '.' . $avatarPath->getClientOriginalExtension();
             $directory = 'public/images/avatars';
             $storedImagePath = $avatarPath->storeAs($directory, $imageName);

             $userData['avatar'] = 'images/avatars/'. $imageName;
         }


         $user->update($validatedData);


         return response()->json(['message' => 'User updated successfully.', 'user' => $user]);
     }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();

        // $adminRole = OrganizationModel::where('code', 'ADMIN')->first();

        // if ($user->organization_id === $adminRole->id) {


                $userToDelete = User::find($id);
            if( $userToDelete){
                $userToDelete->delete();
                return response()->json(['message' => 'User deleted successfully.']);
            }else{
                return response()->json(['error' => 'User not found.'], 404);
            }


        // } else {

            return response()->json(['error' => 'Unauthorized.'], 403);
        // }
    }

    public function getUsersByGroup($groupId)
    {
      // Get a list of users whose organization_id is $groupId
        $users = User::where('organization_id', $groupId)->get();

      // Check if there are users in that organization
        if ($users->isNotEmpty()) {
            return response()->json(['users' => $users]);
        } else {
            return response()->json(['message' => 'No users found in this organization.']);
        }
    }

    public function getActiveUser($userId)
    {
        $authUser = Auth::user();
        $user = User::find($userId);
        $adminRole = OrganizationModel::where('code', 'ADMIN')->first();

        // Check if the authenticated user has admin or upper management permissions
        if ($authUser->id === $user->manager_id || $authUser->organization_id === $adminRole->id) {
            // Toggle the status of the user
            $newStatus = $user->status == '0' ? '1' : '0';
            $user->update(['status' => $newStatus]);

            return response()->json(['user' => $user]);
        }

        return response()->json(['error' => 'Unauthorized.'], 403);
    }

    public function resetPassword(Request $request)
{
    // Validate incoming request data
    $validatedData = $request->validate([
        'current_password' => 'required|string|min:8',
        'new_password' => 'required|string|min:8|confirmed',
    ]);

    // Retrieve the authenticated user
    $user = Auth::user();

    // Check if the current password matches the user's stored password
    if (!Hash::check($validatedData['current_password'], $user->password)) {
        return response()->json(['error' => 'Current password is incorrect.'], 400);
    }

    // Update the user's password with the new hashed password
    $user->update(['password'=> Hash::make($validatedData['new_password'])]);

    // Return a success message after updating the password
    return response()->json(['message' => 'Password updated successfully.']);
}



    public function changeRole(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'user_id' => 'required|integer',
            'new_role_id' => 'required|integer',
        ]);

        // Retrieve the user based on the provided user_id
        $user = User::find($validatedData['user_id']);

        // Check if the user exists
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        // Update the user's role to the new_role_id
        $user->update(['role_id' => $validatedData['new_role_id']]);

        // Return a success message along with the updated user data
        return response()->json(['message' => 'User role updated successfully.', 'user' => $user]);
    }
    public function userActivities()
    {

        $user = Auth::user();


        $activities = Audit::where('user_id', $user->id)->latest()->get();


        return response()->json(['activities' => $activities]);
    }
    public function userDeleteActivities($id)
    {

        $user = Auth::user();

        $activity = Audit::where('id', $id)->where('user_id', $user->id)->first();

        if ($activity) {

            $activity->delete();
            return response()->json(['message' => 'Activity deleted successfully'], 200);
        } else {

            return response()->json(['message' => 'Activity not found or not authorized'], 404);
        }
    }

    public function searchUser(Request $request){

        // Xử lý khi không có tham số nào được cung cấp
if (empty($request->all())) {

    $users = User::paginate(10);
    $users->load('belongward.district.province');

  $a=  UserResource::collection($users);
    return    $a;

}

        $params = $request->validate([
            "perpage" => "required_without_all:username,manager_id,organization_id,name,phone_number,identity_number,date_start_working",
            "username" => "required_without_all:perpage,manager_id,organization_id,name,phone_number,identity_number,date_start_working",
            'manager_id' => "required_without_all:perpage,username,organization_id,name,phone_number,identity_number,date_start_working",
            'organization_id' => "required_without_all:perpage,username,manager_id,name,phone_number,identity_number,date_start_working",
            'name' => "required_without_all:perpage,username,manager_id,organization_id,phone_number,identity_number,date_start_working",
            'phone_number' => "required_without_all:perpage,username,manager_id,organization_id,name,identity_number,date_start_working",
            'identity_number' => "required_without_all:perpage,username,manager_id,organization_id,name,phone_number,date_start_working",
            'date_start_working' => "required_without_all:perpage,username,manager_id,organization_id,name,phone_number,identity_number",
        ]);
        $perPage = $request->input('perpage') ?? 10;
        $query = User::query();

        if ($request->name) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->manager_id) {
            $query->where('manager_id', $request->manager_id);
        }
        if ($request->username) {
            $query->where('username', 'like', '%' . $request->username . '%');
        }
        if ($request->organization_id) {
            $query->where('organization_id',  $request->organization_id);
        }
        if ($request->phone_number) {
            $query->where('phone_number', 'like', '%' . $request->phone_number . '%');
        }
        if ($request->identity_number) {
            $query->where('identity_number', 'like', '%' . $request->identity_number . '%');
        }
        if ($request->date_start_working) {
            $query->where('date_start_working', 'like', '%' . $request->date_start_working . '%');
        }
        $query->with('organization', 'belongward.district.province', 'roles');
        $results  =  $query->paginate($perPage);




        return response()->json($results);

    }


}
