<?php

namespace App\Http\Controllers\Target;

use App\Http\Controllers\Controller;
use App\Http\Requests\DailyReportsRequests;
use App\Http\Resources\DailyReportsResource;
use App\Models\DailyReportsModel;
use App\Models\OrganizationModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DayReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function getAllManagedUsers($user)
    // {
    //     echo(0);
    //     $directlyManagedUsers = $user->managedUsers->pluck('id')->toArray();
    //     $allManagedUsers = $directlyManagedUsers;

    //     foreach ($directlyManagedUsers as $userId) {
    //         $nextUser = User::find($userId);
    //         if ($nextUser) {
    //             $allManagedUsers = array_merge($allManagedUsers, $this->getAllManagedUsers($nextUser));
    //         }
    //     }

    //     return $allManagedUsers;
    // }
    //  $allManagedUsers = $this->getAllManagedUsers($user);


    //     $allManagedUsers1 = array_unique($allManagedUsers);
    public function index(Request $request)
    {

        $perPage = $request->input('perPage', 10);

        $user = Auth::guard('api')->user();
        $organization =  OrganizationModel::find($user->organization_id);

        // Check if the user is a manager or administrator
        if (strpos($organization->code, "SM") != true) {

            $dayreport = DailyReportsModel::with('belong')->where('user_id', $user->id)->paginate($perPage);
            // Assuming $dailytodo is an Eloquent collection, you can convert it to JSON like this:
            $data=DailyReportsResource::collection( $dayreport);
            
            return response()->json($data);
        } else {
            $maxLevel = OrganizationModel::max('level');
            // Get the ids of all users under the current user's direct authority 
            $directlyManagedUsers = $user->managedUsers->pluck('id')->toArray();
            // Create an array containing all those users
            $allManagedUsers = $directlyManagedUsers;
            $numberOfLoops = $maxLevel + 10 - $user->organization_id;

            // Create an array containing the current user
            $currentLevelUsers = $directlyManagedUsers;
            for ($i = 0; $i < $numberOfLoops; $i++) {
                // Create an empty array to store the next user's information
                $nextLevelUsers = [];
                // Run a loop to check all elements in the array   
                foreach ($currentLevelUsers as $userId) {
                    // Get the id of all managed users directly from the added user
                    $managedUsers = User::find($userId)->managedUsers->pluck('id')->toArray();
                    // Merge the next level users array into the all managed users array.
                    $nextLevelUsers = array_merge($nextLevelUsers, $managedUsers);
                }
                // Merge all managed users with allManagedUsers array    
                $allManagedUsers = array_merge($allManagedUsers, $nextLevelUsers);
                // Update $currentLevelUsers to loop through the next level in the next loop
                $currentLevelUsers = $nextLevelUsers;
            }
            $allManagedUsers[] = $user->id;
            // Remove duplicate id
            $allManagedUsers = array_unique($allManagedUsers);

            $dayreport = DailyReportsModel::with('belong')->whereIn('user_id', $allManagedUsers)->paginate($perPage);
            
        }
        $data=DailyReportsResource::collection( $dayreport);
        return response()->json( $data);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $user = Auth::user();
        return response()->json($user);
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(DailyReportsRequests $request)
    {
        //
        $user = Auth::guard('api')->user();
        $organization =  OrganizationModel::find($user->organization_id);

        // Check if the user is a manager or administrator
        if (strpos($organization->code, "SM") == true || strpos($organization->code, "ADMIN") == true) {
            return response()->json(['message' => 'You do not have sufficient access rights.'], 403);
        }
        $request['user_id'] = Auth::id();

        $dayreport =   DailyReportsModel::create($request->all());

        return response()->json(['message' => 'Day Report insert successfully', 'dayreport' => $dayreport], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $dayreport)
    {

        $DailyReports = DailyReportsModel::with('belong')->find($dayreport);


        if (!$DailyReports) {
            return response()->json(['error' => 'Report not found'], 404);
        }

        $user = Auth::guard('api')->user();
        $organization =  OrganizationModel::find($user->organization_id);

        // Check if the user is a manager or administrator
        if (strpos($organization->code, "SM") != true) {
            if ($user->id == $DailyReports->user_id) {
                return response()->json($DailyReports);
            } else {

                return response()->json(['message' => 'You have no reports'], 403);
            }
        } else {
            $maxLevel = OrganizationModel::max('level');
            // Get the ids of all users under the current user's direct authority 
            $directlyManagedUsers = $user->managedUsers->pluck('id')->toArray();
            // Create an array containing all those users
            $allManagedUsers = $directlyManagedUsers;
            $numberOfLoops = $maxLevel + 10 - $user->organization_id;

            // Create an array containing the current user
            $currentLevelUsers = $directlyManagedUsers;
            for ($i = 0; $i < $numberOfLoops; $i++) {
                // Create an empty array to store the next user's information
                $nextLevelUsers = [];
                // Run a loop to check all elements in the array   
                foreach ($currentLevelUsers as $userId) {
                    // Get the id of all managed users directly from the added user
                    $managedUsers = User::find($userId)->managedUsers->pluck('id')->toArray();
                    // Merge the next level users array into the all managed users array.
                    $nextLevelUsers = array_merge($nextLevelUsers, $managedUsers);
                }
                // Merge all managed users with allManagedUsers array    
                $allManagedUsers = array_merge($allManagedUsers, $nextLevelUsers);
                // Update $currentLevelUsers to loop through the next level in the next loop
                $currentLevelUsers = $nextLevelUsers;
            }
            $allManagedUsers[] = $user->id;
            // Remove duplicate id
            $allManagedUsers = array_unique($allManagedUsers);
            if (!in_array($DailyReports->user_id, $allManagedUsers)) {
                return response()->json(['message' => 'You do not have permission to see this file'], 403);
            }
        }
        return response()->json($DailyReports);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $DailyReports = DailyReportsModel::with('belong')->find($id);

        if (!$DailyReports) {
            return response()->json(['error' => 'Daily reports not found'], 404);
        }

        $user = Auth::guard('api')->user();
        $organization =  OrganizationModel::find($user->organization_id);

        // Check if the user is a manager or administrator
        if (strpos($organization->code, "SM") == true) {
            return response()->json(['message' => 'You do not have permission to see this file'], 403);
        } else {
            $maxLevel = OrganizationModel::max('level');
            // Get the ids of all users under the current user's direct authority 
            $directlyManagedUsers = $user->managedUsers->pluck('id')->toArray();
            // Create an array containing all those users
            $allManagedUsers = $directlyManagedUsers;
            $numberOfLoops = $maxLevel + 10 - $user->organization_id;

            // Create an array containing the current user
            $currentLevelUsers = $directlyManagedUsers;
            for ($i = 0; $i < $numberOfLoops; $i++) {
                // Create an empty array to store the next user's information
                $nextLevelUsers = [];
                // Run a loop to check all elements in the array   
                foreach ($currentLevelUsers as $userId) {
                    // Get the id of all managed users directly from the added user
                    $managedUsers = User::find($userId)->managedUsers->pluck('id')->toArray();
                    // Merge the next level users array into the all managed users array.
                    $nextLevelUsers = array_merge($nextLevelUsers, $managedUsers);
                }
                // Merge all managed users with allManagedUsers array    
                $allManagedUsers = array_merge($allManagedUsers, $nextLevelUsers);
                // Update $currentLevelUsers to loop through the next level in the next loop
                $currentLevelUsers = $nextLevelUsers;
            }
            $allManagedUsers[] = $user->id;
            // Remove duplicate id
            $allManagedUsers = array_unique($allManagedUsers);
            if (!in_array($DailyReports->user_id, $allManagedUsers)) {
                return response()->json(['message' => 'You do not have permission to see this file'], 403);
            }
        }
        return response()->json($DailyReports);
    }

    /**
     * Update the specified resource in storage.
     */

    function validateFieldIfExists(Request $request, $fieldName, $rules)
    {
        if ($request->has($fieldName)) {
            $validatedData = $request->validate([$fieldName => $rules]);
            return $validatedData[$fieldName];
        }
        return null;
    }
    public function update(Request $request, string $id)
    {
        //
        $date = $this->validateFieldIfExists($request, 'date', 'required|string');
        $reporting = $this->validateFieldIfExists($request, 'period_time', 'required|string');
        $appCrc = $this->validateFieldIfExists($request, 'app-crc', 'required|string');
        $loanCrc = $this->validateFieldIfExists($request, 'loan-crc', 'required|string');
        $appPlxs = $this->validateFieldIfExists($request, 'app-plxs', 'required|string');
        $loanPlxs = $this->validateFieldIfExists($request, 'loan-plxs', 'required|string');
        $loanPlxs = $this->validateFieldIfExists($request, 'amount_plxs', 'required|string');

        $amountXs = $this->validateFieldIfExists($request, 'amount_banca', 'required|string');
        $bancaXs = $this->validateFieldIfExists($request, 'loan_ctbs', 'required|string');
        $ratioBanca = $this->validateFieldIfExists($request, 'conver_banca', 'required|string');
        $ratioCtbs = $this->validateFieldIfExists($request, 'conver_ctbs', 'required|string');

        $DailyReports = DailyReportsModel::find($id);

        if (!$DailyReports) {
            return response()->json(['error' => 'Operational goal not found'], 404);
        }

        $user = Auth::guard('api')->user();
        $organization =  OrganizationModel::find($user->organization_id);

        // Check if the user is a manager or administrator
        if (strpos($organization->code, "SM") == true) {
            return response()->json(['message' => 'You do not have permission to see this file'], 403);
        } else {
            $maxLevel = OrganizationModel::max('level');
            // Get the ids of all users under the current user's direct authority 
            $directlyManagedUsers = $user->managedUsers->pluck('id')->toArray();
            // Create an array containing all those users
            $allManagedUsers = $directlyManagedUsers;
            $numberOfLoops = $maxLevel + 10 - $user->organization_id;

            // Create an array containing the current user
            $currentLevelUsers = $directlyManagedUsers;
            for ($i = 0; $i < $numberOfLoops; $i++) {
                // Create an empty array to store the next user's information
                $nextLevelUsers = [];
                // Run a loop to check all elements in the array   
                foreach ($currentLevelUsers as $userId) {
                    // Get the id of all managed users directly from the added user
                    $managedUsers = User::find($userId)->managedUsers->pluck('id')->toArray();
                    // Merge the next level users array into the all managed users array.
                    $nextLevelUsers = array_merge($nextLevelUsers, $managedUsers);
                }
                // Merge all managed users with allManagedUsers array    
                $allManagedUsers = array_merge($allManagedUsers, $nextLevelUsers);
                // Update $currentLevelUsers to loop through the next level in the next loop
                $currentLevelUsers = $nextLevelUsers;
            }
            $allManagedUsers[] = $user->id;
            // Remove duplicate id
            $allManagedUsers = array_unique($allManagedUsers);
            if (!in_array($DailyReports->user_id, $allManagedUsers)) {
                return response()->json(['message' => 'You do not have permission to see this file'], 403);
            }
        }
        $DailyReports->update($request->all());
        return response()->json(['message' => 'Day Report update successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $DailyReports = DailyReportsModel::find($id);

        if (!$DailyReports) {
            return response()->json(['error' => 'Daily report not found'], 404);
        }

        $user = Auth::guard('api')->user();
        $organization =  OrganizationModel::find($user->organization_id);

        // Check if the user is a manager == true no manager
        if (strpos($organization->code, "SM") == true) {
            return response()->json(['message' => 'You do not have permission to see this file'], 403);
        } else {
            $maxLevel = OrganizationModel::max('level');
            // Get the ids of all users under the current user's direct authority 
            $directlyManagedUsers = $user->managedUsers->pluck('id')->toArray();
            // Create an array containing all those users
            $allManagedUsers = $directlyManagedUsers;
            $numberOfLoops = $maxLevel + 10 - $user->organization_id;

            // Create an array containing the current user
            $currentLevelUsers = $directlyManagedUsers;
            for ($i = 0; $i < $numberOfLoops; $i++) {
                // Create an empty array to store the next user's information
                $nextLevelUsers = [];
                // Run a loop to check all elements in the array   
                foreach ($currentLevelUsers as $userId) {
                    // Get the id of all managed users directly from the added user
                    $managedUsers = User::find($userId)->managedUsers->pluck('id')->toArray();
                    // Merge the next level users array into the all managed users array.
                    $nextLevelUsers = array_merge($nextLevelUsers, $managedUsers);
                }
                // Merge all managed users with allManagedUsers array    
                $allManagedUsers = array_merge($allManagedUsers, $nextLevelUsers);
                // Update $currentLevelUsers to loop through the next level in the next loop
                $currentLevelUsers = $nextLevelUsers;
            }
            $allManagedUsers[] = $user->id;
            // Remove duplicate id
            $allManagedUsers = array_unique($allManagedUsers);
            if (!in_array($DailyReports->user_id, $allManagedUsers)) {
                return response()->json(['message' => 'You do not have permission to see this file'], 403);
            }
        }


        $DailyReports->delete();
        return response()->json(['message' => 'Item deleted successfully'], 200);
    }
}
