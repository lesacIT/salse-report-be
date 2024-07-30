<?php

namespace App\Http\Controllers\Vehicle;

use App\Http\Controllers\Controller;
use App\Http\Requests\CapacityRequest;
use App\Models\CapacityModel;
use Illuminate\Http\Request;

class CapacityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $data = CapacityModel::with('belong.belong')->get();
        return response()->json(['data' => $data], 200);
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
    public function store(CapacityRequest $request)
    {
        //
        $automaker1 = [
            'range_of_vehicle_id' => $request->input('range_of_vehicle_id'),
            'name' => $request->input('name'),
            'money' => $request->input('money'),
            'quantity' => $request->input('quantity'),
        ];
        $automaker =    CapacityModel::create($automaker1);
        return response()->json(['message' => 'Automaker created successfully', 'data' => $automaker]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $automaker = CapacityModel::find($id);
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
        $automaker = CapacityModel::find($id);
        if (!$automaker) {
            return response()->json(['message' => 'Automaker not found'], 404);
        }
        return response()->json(['message' => 'Automaker found', 'data' => $automaker]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CapacityRequest $request, string $id)
    {
        //
        $automaker1 = [
            'range_of_vehicle_id' => $request->input('range_of_vehicle_id'),
            'name' => $request->input('name'),
            'money' => $request->input('money'),
            'quantity' => $request->input('quantity'),
        ];
        $automaker = CapacityModel::find($id);
        $automaker->update($automaker1);
        return response()->json(['message' => 'Automaker updated successfully', 'data' => $automaker]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $activity = CapacityModel::find($id);

        if (!$activity) {

            return response()->json(['message' => 'No activity was found with the specified ID'], 404);
        } else {
            $activity->delete();
            return response()->json($activity, 200);
        }
    }
}
