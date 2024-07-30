<?php

namespace App\Http\Controllers\Vehicle;

use App\Http\Controllers\Controller;
use App\Http\Requests\AutomakerRequest;
use App\Models\AutomakerModel;
use App\Models\CapacityModel;
use App\Models\RangeOfVehicleModel;
use Illuminate\Http\Request;

class AutomakerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $automaker = AutomakerModel::with('many.many')->paginate($perPage);
        return response()->json($automaker);
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
    public function store(AutomakerRequest $request)
    {
        //
        $automaker = [
            'brand_name' => $request->input('brand_name'),
            'date' => $request->input('date')
        ];
        AutomakerModel::create($automaker);
        return response()->json(['message' => 'Automaker created successfully', 'data' => $automaker]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

        $automaker = AutomakerModel::find($id);
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
        $automaker = AutomakerModel::find($id);
        if (!$automaker) {
            return response()->json(['message' => 'Automaker not found'], 404);
        }
        return response()->json(['message' => 'Automaker found', 'data' => $automaker]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AutomakerRequest $request, string $id)
    {
        //
        $automakervalidate = [
            'brand_name' => $request->input('brand_name'),
            'date' => $request->input('date')
        ];
        $automaker = AutomakerModel::find($id);
        $automaker->update($automakervalidate);
        return response()->json(['message' => 'Automaker updated successfully', 'data' => $automaker]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        $activity = AutomakerModel::find($id);

        if (!$activity) {

            return response()->json(['message' => 'No activity was found with the specified ID'], 404);
        } else {
            $activity->delete();
            return response()->json($activity, 200);
        }
    }

    public function   PriceVehicle(Request $request)
    {
        $automaker1 = $request->query('automaker');
        if ($automaker1) {

            $automaker = AutomakerModel::where('brand_name', $automaker1)->first();
            $rangeOfVehicle1 = $request->query('range-of-vehicle');
            if ($rangeOfVehicle1) {

                $capacity1 = $request->query('capacity');
                $rangeOfVehicle = RangeOfVehicleModel::where('name', $rangeOfVehicle1)
                    ->where('automaker_id', $automaker->id)
                    ->first();
                $capacities = CapacityModel::where('range_of_vehicle_id', $rangeOfVehicle->id)->get();

                if (count($capacities) == 1) {
                    response()->json(['data' => $capacities], 200);
                }

                if ($capacity1) {
                    $capacity = CapacityModel::select('money')->where('name', $capacity1)->where('range_of_vehicle_id',  $rangeOfVehicle->id)->first();
                    return response()->json(['data' => $capacity], 200);
                }
            }
        }
    }
}
