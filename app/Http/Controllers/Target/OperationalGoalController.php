<?php

namespace App\Http\Controllers\Target;

use App\Http\Controllers\Controller;
use App\Http\Requests\OperationalGoalRequest;
use App\Models\CatDailyActivitieGroupsModel;
use App\Models\CatDailyActivitiesModel;
use App\Models\CatTimeSlotModel;
use App\Models\DailyTodoDetailsModel;
use App\Models\DailyTodoModel;
use App\Models\OrganizationModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OperationalGoalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 
        //http://127.0.0.1:8000/api/operational-goal?page=2&perPage=3
        $perPage = $request->input('perPage', 10);

        $user = Auth::guard('api')->user();
        $organization =  OrganizationModel::find($user->organization_id);
        $dailytodo = DailyTodoModel::with('have')->where('user_id', $user->id)->paginate($perPage);

        // Check if the user is a manager or administrator
        if (strpos($organization->code, "SM") != true) {
            // Assuming $dailytodo is an Eloquent collection, you can convert it to JSON like this:
            return response()->json($dailytodo);
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

            $dailytodo = DailyTodoModel::with('have')->whereIn('user_id', $allManagedUsers)->paginate($perPage);
        }
        return response()->json($dailytodo);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $user = Auth::guard('api')->user();
        $organization =  OrganizationModel::find($user->organization_id);
        //duoc tim thay
        if (strpos($organization->code, "SM") == true) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $timeslot = CatTimeSlotModel::all();
        $groups = CatDailyActivitieGroupsModel::with('activities')->get();

        return response()->json(['groups' => $groups, 'timeslot' => $timeslot, 'auth' => $user]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(OperationalGoalRequest $request)
    {
        // OperationalGoalRequest

        $user = Auth::guard('api')->user();
        $organization =  OrganizationModel::find($user->organization_id);

        // Check if the user is a manager or administrator
        //duoc tim thay
        if (strpos($organization->code, "SM") == true || strpos($organization->code, "ADMIN") == true) {
            return response()->json(['message' => 'You do not have sufficient access rights.'], 403);
        }
        $dailyToDo =  DailyTodoModel::create([
            'user_id' => $user->id,
            'date' => $request->input('date')
        ]);
        $dailyToDoDetail = new DailyTodoDetailsModel();
        $dailyToDoDetail->create([
            'daily_todo_id' => $dailyToDo->id,
            'time_slot_id' => $request->input('time_slot_id'),
            'daily_activity_id' => $request->input('daily_activity_id'),
            'place' => $request->input('place'),
            'detail' => $request->input('detail'),
            'finished' => 'null'
        ]);
        $dailyToDoDetail->create([
            'daily_todo_id' => $dailyToDo->id,
            'time_slot_id' => $request->input('time_slot_id2'),
            'daily_activity_id' => $request->input('daily_activity_id2'),
            'place' => $request->input('place2'),
            'detail' => $request->input('detail2'),
            'finished' => 'null'
        ]);
        $dailyToDoDetail->create([
            'daily_todo_id' => $dailyToDo->id,
            'time_slot_id' => $request->input('time_slot_id3'),
            'daily_activity_id' => $request->input('daily_activity_id3'),
            'place' => $request->input('place3'),
            'detail' => $request->input('detail3'),
            'finished' => 'null'
        ]);
        $dailyToDoDetail->create([
            'daily_todo_id' => $dailyToDo->id,
            'time_slot_id' => $request->input('time_slot_id4'),
            'daily_activity_id' => $request->input('daily_activity_id4'),
            'place' => $request->input('place4'),
            'detail' => $request->input('detail4'),
            'finished' => 'null'
        ]);

        return response()->json(['message' => 'Add a successful performance goal'], 200);
    }



    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $dailytodo = DailyTodoModel::with('have', 'user')->find($id);

        if (!$dailytodo) {
            return response()->json(['error' => 'Daily todo goal not found'], 404);
        }

        $user = Auth::guard('api')->user();

        $organization =  OrganizationModel::find($user->organization_id);

        // Check if the user is a manager or administrator
        // 
        if (strpos($organization->code, "SM") != true) {

            if ($user->id == $dailytodo->user_id) {

                return response()->json($dailytodo);
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

            if (!in_array($dailytodo->user_id, $allManagedUsers)) {
                return response()->json(['message' => 'You do not have permission to see this file'], 403);
            }
        }
        return response()->json($dailytodo);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $dailytodo = DailyTodoModel::with('have')->find($id);

        if (!$dailytodo) {
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
            if (!in_array($dailytodo->user_id, $allManagedUsers)) {
                return response()->json(['message' => 'You do not have permission to see this file'], 403);
            }
        }
        return response()->json($dailytodo);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  $operational_goal)
    {

        $DailyTodo = DailyTodoModel::find($operational_goal);
        if (!$DailyTodo) {
            return response()->json(['message' => 'Operational goal not found']);
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

            if (!in_array($DailyTodo->user_id, $allManagedUsers)) {
                return response()->json(['message' => 'You do not have permission to see this file'], 403);
            }
        }
        $daily_todo_id = $DailyTodo['id'];
        $dailyToDoDetail =  DailyTodoDetailsModel::find($request->input('id_detail'));
        $dailyToDoDetail->update([
            'daily_todo_id' => $daily_todo_id,
            'time_slot_id' => $request->input('time_slot_id'),
            'daily_activity_id' => $request->input('daily_activity_id'),
            'place' => $request->input('place'),
            'detail' => $request->input('detail'),
            'finished' => 'null'
        ]);
        $dailyToDoDetail =  DailyTodoDetailsModel::find($request->input('id_detail2'));
        $dailyToDoDetail->update([
            'daily_todo_id' => $daily_todo_id,
            'time_slot_id' => $request->input('time_slot_id2'),
            'daily_activity_id' => $request->input('daily_activity_id2'),
            'place' => $request->input('place2'),
            'detail' => $request->input('detail2'),
            'finished' => 'null'
        ]);
        $dailyToDoDetail =  DailyTodoDetailsModel::find($request->input('id_detail3'));
        $dailyToDoDetail->update([
            'daily_todo_id' => $daily_todo_id,
            'time_slot_id' => $request->input('time_slot_id3'),
            'daily_activity_id' => $request->input('daily_activity_id3'),
            'place' => $request->input('place3'),
            'detail' => $request->input('detail3'),
            'finished' => 'null'
        ]);
        $dailyToDoDetail =  DailyTodoDetailsModel::find($request->input('id_detail4'));
        $dailyToDoDetail->update([
            'daily_todo_id' => $daily_todo_id,
            'time_slot_id' => $request->input('time_slot_id4'),
            'daily_activity_id' => $request->input('daily_activity_id4'),
            'place' => $request->input('place4'),
            'detail' => $request->input('detail4'),
            'finished' => 'null'
        ]);
        return response()->json(['message' => 'Operational Goals updated successfully'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($operational_goal)
    {
        $DailyTodo = DailyTodoModel::find($operational_goal);

        if (!$DailyTodo) {
            return response()->json(['error' => 'Operational goal not found'], 404);
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
            if (!in_array($DailyTodo->user_id, $allManagedUsers)) {
                return response()->json(['message' => 'You do not have permission to see this file'], 403);
            }
        }
        $DailyTodo->delete();
        DailyTodoDetailsModel::where('daily_todo_id', $DailyTodo->id)->delete();
        return response()->json(['message' => 'Item deleted successfully'], 200);
    }
}
