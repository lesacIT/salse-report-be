<?php

namespace App\Http\Controllers\Vehicle;

use App\Http\Controllers\Controller;
use App\Http\Requests\RangeOfVehicleRequest;
use App\Models\RangeOfVehicleModel;
use Illuminate\Http\Request;

class RangeOfVehicleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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
    public function store(RangeOfVehicleRequest $request)
    {
        //
        $automaker = [
            'automaker_id' => $request->input('automaker_id'),
            'name' => $request->input('name')
        ];
        RangeOfVehicleModel::create($automaker);
        return response()->json(['message' => 'Automaker created successfully', 'data' => $automaker]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $automaker = RangeOfVehicleModel::find($id);
        if (!$automaker) {
            return response()->json(['message' => 'Automaker not found'], 404);
        }
        return response()->json(['message' => 'Automaker found', 'data' => $automaker]);
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $automaker = RangeOfVehicleModel::find($id);
        if (!$automaker) {
            return response()->json(['message' => 'Automaker not found'], 404);
        }
        return response()->json(['message' => 'Automaker found', 'data' => $automaker]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RangeOfVehicleRequest $request, string $id)
    {
        //
        $automakervalidate = [
            'name' => $request->input('name'),
        ];
        $automaker = RangeOfVehicleModel::find($id);
        $automaker->update($automakervalidate);
        return response()->json(['message' => 'Automaker updated successfully', 'data' => $automaker]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $activity = RangeOfVehicleModel::find($id);

        if (!$activity) {

            return response()->json(['message' => 'No activity was found with the specified ID'], 404);
        } else {
            $activity->delete();
            return response()->json($activity, 200);
        }
    }
}
