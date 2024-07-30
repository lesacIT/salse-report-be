<?php

namespace App\Http\Controllers\Target;

use App\Http\Controllers\Controller;
use App\Http\Resources\CatTimeSlotResource;
use App\Models\CatTimeSlotModel;
use Illuminate\Http\Request;

class CatTimeSlotController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:CatTimeSlot.index')->only('index');
        $this->middleware('permission:CatTimeSlot.store')->only('store');
        $this->middleware('permission:CatTimeSlot.show')->only('show');
        $this->middleware('permission:CatTimeSlot.update')->only('update');
        $this->middleware('permission:CatTimeSlot.delete')->only('destroy');
    }

    public function index()
    {
        $timeslot = CatTimeSlotModel::all();


        $data =  CatTimeSlotResource::collection($timeslot);
        return response()->json(['category-time-period' =>   $data], 200);
    }

    public function store(Request $request)
    {

        $request->validate([
            'time_slot' => 'required',
            'period' => 'required',
        ]);

        $timeslot =   CatTimeSlotModel::create($request->only('time_slot', 'period'));

        if ($timeslot) {
            $group = new  CatTimeSlotResource($timeslot);
            return response()->json([
                'message' => 'Success', 'data' => $group
            ], 200);
        } else {
            return response()->json([
                'message' => 'Failure',
            ], 400);
        }
    }

    public function show(string $id)
    {
        //
        $timeslot = CatTimeSlotModel::find($id);

        if ($timeslot) {

            $group = new  CatTimeSlotResource($timeslot);

            return response()->json(['category-time-period' =>   $group], 200);
        }
        return response()->json(['message' => 'Time not found'], 404);
    }

    // public function edit(string $id)
    // {


    //     $timeslot = CatTimeSlotModel::find($id);



    //     if (!$timeslot) {
    //         return response()->json(['message' => 'Time not found'], 404);
    //     }

    //     return response()->json(['category-time-period' =>   $timeslot], 200);
    // }


    public function update(Request $request, string $id)
    {
        $request->validate([
            'time_slot' => 'required|string',
            'period' => 'required|string',
        ]);
        $CatTimeSlot = CatTimeSlotModel::find($id);
        $CatTimeSlot->update($request->only('time_slot', 'period'));
        $group = new  CatTimeSlotResource($CatTimeSlot);

        return response()->json(['timeslot' =>   $group], 200);
    }

    public function destroy(string $id)
    {
        $timeslot = CatTimeSlotModel::find($id);

        if ($timeslot) {

            $timeslot->delete();

            return response()->json(['message' => 'Time slot deleted successfully'], 200);
        } else {

            return response()->json(['message' => 'No timeslot was found with the specified ID'], 404);
        }
    }
}
