<?php

namespace App\Http\Controllers\Target;

use App\Http\Controllers\Controller;
use App\Http\Resources\CatDailyActivityResource;
use App\Models\CatDailyActivitieGroupsModel;
use App\Models\CatDailyActivitiesModel;
use Illuminate\Http\Request;

class CatDailyActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $catdailactivity = CatDailyActivitiesModel::with('belong')->get();
        $data =   CatDailyActivityResource::collection($catdailactivity);
        return response()->json(['catdailactivity' =>   $catdailactivity], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $activities = CatDailyActivitieGroupsModel::all();
        return response()->json(['activities' => $activities], 200);
    }


    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'group_id' => 'required|exists:cat_daily_activitie_groups,id',
            'title' => 'required|string',
            'description' => 'required|string',
        ]);
        $catdailactivity =   CatDailyActivitiesModel::create([
            'group_id' => $validated['group_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],

        ]);
        $catdailactivity->load('belong');
        $data  = new  CatDailyActivityResource($catdailactivity);
        return response()->json(['category-daily-activity' =>   $data], 200);
    }

    public function show(string $id)
    {

        $activity = CatDailyActivitiesModel::find($id);
        $activity->load('belong');

        if (!$activity) {
            return response()->json(['message' => 'Activity not found'], 404);
        }
        $data  = new  CatDailyActivityResource($activity);
        return response()->json(['category-daily-activity' => $data], 200);
    }


    // public function edit(string $id)
    // {
    //     //
    //     $activity = CatDailyActivitiesModel::with('belong')->find($id);

    //     if (!$activity) {
    //         return response()->json(['message' => 'Activity not found'], 404);
    //     }

    //     return response()->json(['category-daily-activity' => $activity], 200);
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
        ]);
        $activity = CatDailyActivitiesModel::find($id);
        if ($activity) {
            $activity->update($request->only('title', 'description'));
            $activity->load('belong');
            $data  = new  CatDailyActivityResource($activity);

            return response()->json(['activity' =>   $data], 200);
        }
        return response()->json(['message' => 'No activity was found with the specified ID'], 404);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $activity = CatDailyActivitiesModel::find($id);

        if (!$activity) {

            return response()->json(['message' => 'No activity was found with the specified ID'], 404);
        } else {
            $activity->delete();
            return response()->json(['message' => 'Activity deleted successfully'], 200);
        }
    }
}
