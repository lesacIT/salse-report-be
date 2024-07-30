<?php

namespace App\Http\Controllers\Target;

use App\Http\Controllers\Controller;
use App\Http\Resources\CatDailyActivitieGroupsResource;
use App\Models\CatDailyActivitieGroupsModel;
use Illuminate\Http\Request;

class CatDailyActivitieGroupsController extends Controller
{

    public function index()
    {
        $activities = CatDailyActivitieGroupsModel::all();

        $activities->load('activities');

        $data =  CatDailyActivitieGroupsResource::collection($activities);

        return response()->json(['activities' => $data], 200);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
        ]);
        $catdailactivity =   CatDailyActivitieGroupsModel::create([
            'title' => $validated['title'],
            'description' => $validated['description'],

        ]);
        $data = new  CatDailyActivitieGroupsResource($catdailactivity);
        return response()->json(['category-daily-activity-groups' =>   $data], 200);
    }


    public function show(string $id)
    {

        $activity = CatDailyActivitieGroupsModel::find($id);

        if (!$activity) {

            return response()->json(['message' => 'Activity not found'], 404);
        }
        $activity->load('activities');

        $data = new  CatDailyActivitieGroupsResource($activity);

        return response()->json(['category-daily-activity-group' => $data], 200);
    }

    // public function edit(string $id)
    // {
    //     //
    //     $activity = CatDailyActivitieGroupsModel::with('activities')->find($id);

    //     if (!$activity) {
    //         return response()->json(['message' => 'Activity not found'], 404);
    //     }

    //     return response()->json(['category-time-period' => $activity], 200);
    // }

    public function update(Request $request, string $id)
    {

        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
        ]);
        $activity = CatDailyActivitieGroupsModel::find($id);


        if ($activity) {

            $activity->update($request->only('title', 'description'));

            $activity->load('belong');

            $data  = new  CatDailyActivitieGroupsResource($activity);

            return response()->json(['activity' =>   $data], 200);
        }

        return response()->json(['activity' =>   $activity], 200);
    }

    public function destroy(string $id)
    {
        $activity = CatDailyActivitieGroupsModel::find($id);

        if ($activity) {

            $activity->delete();

            return response()->json(['message' => 'Activity groups deleted successfully'], 200);
        } else {

            return response()->json(['message' => 'No activity was found with the specified ID'], 404);
        }
    }
}
