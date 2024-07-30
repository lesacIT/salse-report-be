<?php

namespace App\Http\Controllers\Target;

use App\Http\Controllers\Controller;
use App\Http\Requests\BusinessGoalRequest;
use App\Models\DailyTargetsModel;
use App\Models\OrganizationModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BusinessGoalController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        // 

        $perPage = $request->input('perPage', 10);

        $user = Auth::guard('api')->user();
        $organization =  OrganizationModel::find($user->organization_id);


        // Check if the user is a manager or administrator
        if (strpos($organization->code, "SM") != true) {

            $dailytarget = DailyTargetsModel::with('belong')->where('user_id', $user->id)->paginate($perPage);
            // Assuming $dailytarget is an Eloquent collection, you can convert it to JSON like this:
            return response()->json($dailytarget);
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

            $dailytarget = DailyTargetsModel::with('belong')->whereIn('user_id', $allManagedUsers)->paginate($perPage);
        }
        return response()->json($dailytarget);
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
    public function store(BusinessGoalRequest $request)
    {
        //
        $user = Auth::guard('api')->user();
        $organization =  OrganizationModel::find($user->organization_id);

        // co admin hay sm
        if (strpos($organization->code, "SM") == true || strpos($organization->code, "ADMIN") == true) {
            return response()->json(['message' => 'You do not have sufficient access rights.'], 403);
        }

        $request['user_id'] = Auth::id();

        $tempt = DailyTargetsModel::create([
            "date" => $request->input('date'),
            'user_id' => Auth::id(),
            "crc_app" => $request->input('crc_app'),
            "crc_loan" => $request->input('crc_loan'),
            "plxs_app" => $request->input('plxs_app'),
            "plxs_loan" => $request->input('plxs_loan'),
            "amount_plxs" => $request->input('amount_plxs'),
            "amount_banca" => $request->input('amount_banca'),
            "loan_ctbs" => $request->input('loan_ctbs'),
            'convert_banca' => $request->input('convert_banca'),
            "convert_ctbs" => $request->input('convert_ctbs'),
        ]);

        return response()->json(['message' => 'Add a successful performance goal', 'DailyTargets' => $tempt], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $business_goal)
    {
        // Tìm mục tiêu kinh doanh dựa trên ID
        $dailytarget = DailyTargetsModel::find($business_goal);

        // Nếu không tìm thấy, trả về lỗi 404
        if (!$dailytarget) {
            return response()->json(['error' => 'Operational goal not found'], 404);
        }

        $user = Auth::guard('api')->user();
        $organization =  OrganizationModel::find($user->organization_id);

        // Check if the user is a manager or administrator
        if (strpos($organization->code, "SM") != true) {
            if ($user->id == $dailytarget->user_id) {
                return response()->json($dailytarget);
            } else {
                return response()->json(['message' => 'You have no Operational'], 403);
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
            if (!in_array($dailytarget->user_id, $allManagedUsers)) {
                return response()->json(['message' => 'You do not have permission to see this file'], 403);
            }
        }
        return response()->json($dailytarget);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $dailytarget = DailyTargetsModel::with('belong')->find($id);

        if (!$dailytarget) {
            return response()->json(['error' => 'Operational goal not found'], 404);
        }

        $user = Auth::guard('api')->user();
        $organization =  OrganizationModel::find($user->organization_id);

        // Check if the user is a manager or administrator
        if (strpos($organization->code, "SM") != true) {
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
            if (!in_array($dailytarget->user_id, $allManagedUsers)) {
                return response()->json(['message' => 'You do not have permission to see this file'], 403);
            }
        }
        return response()->json($dailytarget);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $business_goal)
    {
        $dailytarget = DailyTargetsModel::find($business_goal);
        if (!$dailytarget) {
            return response()->json(['error' => 'Business goal not found'], 404);
        }


        $user = Auth::guard('api')->user();
        $organization =  OrganizationModel::find($user->organization_id);

        // Check if the user is a manager or administrator
        if (strpos($organization->code, "SM") != true) {
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
            if (!in_array($dailytarget->user_id, $allManagedUsers)) {
                return response()->json(['message' => 'You do not have permission to see this file'], 403);
            }
            $validatedData = [];


            if ($request->has('crc_app')) {
                $request->validate([
                    'crc_app' => 'required|string',
                ]);
                $validatedData['crc_app'] = $request->input('crc_app');
            }
            // Check and validate for the field crc_loan
            if ($request->has('crc_loan')) {
                $request->validate([
                    'crc_loan' => 'required|string',
                ]);
                $validatedData['crc_loan'] = $request->input('crc_loan');
            }
            // Check and validate for the field plxs_app
            if ($request->has('plxs_app')) {
                $request->validate([
                    'plxs_app' => 'required|string',
                ]);
                $validatedData['plxs_app'] = $request->input('plxs_app');
            }
            // Check and validate for the field plxs_loan
            if ($request->has('plxs_loan')) {
                $request->validate([
                    'plxs_loan' => 'required|string',
                ]);
                $validatedData['plxs_loan'] = $request->input('plxs_loan');
            }
            // Check and validate for the field plxs_loan
            if ($request->has('plxs_loan')) {
                $request->validate([
                    'plxs_loan' => 'required|string',
                ]);
                $validatedData['plxs_loan'] = $request->input('plxs_loan');
            }
            // Check and validate for the field amount_plxs
            if ($request->has('amount_plxs')) {
                $request->validate([
                    'amount_plxs' => 'required|string',
                ]);
                $validatedData['amount_plxs'] = $request->input('amount_plxs');
            }
            // Check and validate for the field banca
            if ($request->has('banca')) {
                $request->validate([
                    'banca' => 'required|string',
                ]);
                $validatedData['banca'] = $request->input('banca');
            }
            // Check and validate for the field loan_ctbs
            if ($request->has('loan_ctbs')) {
                $request->validate([
                    'loan_ctbs' => 'required|string',
                ]);
                $validatedData['loan_ctbs'] = $request->input('loan_ctbs');
            }
            // Check and validate for the field convert_banca
            if ($request->has('convert_banca')) {
                $request->validate([
                    'convert_banca' => 'required|string',
                ]);
                $validatedData['convert_banca'] = $request->input('convert_banca');
            }
            // Check and validate for the field ctbs
            if ($request->has('ctbs')) {
                $request->validate([
                    'ctbs' => 'required|string',
                ]);
                $validatedData['ctbs'] = $request->input('ctbs');
            }
            $dailytarget->update($validatedData);
            return response()->json(['message' => 'Operational Goals updated successfully'], 200);
        }
    }
    /**
     * Remove the specified resource from storage.
     */


    public function destroy($id)
    {
        //
        $DailyReports = DailyTargetsModel::find($id);

        if (!$DailyReports) {
            return response()->json(['error' => 'Daily report not found'], 404);
        }

        $user = Auth::guard('api')->user();
        $organization =  OrganizationModel::find($user->organization_id);

        // Check if the user is a manager == true no manager
        if (strpos($organization->code, "SM") != true) {
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
