<?php

namespace App\Http\Controllers\DLK;

use App\Http\Controllers\Controller;
use App\Models\ListOfTypesDlkModel;
use Illuminate\Http\Request;

class ListOfTypesDlkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    //public function __construct()
    // {
    // $this->middleware('permission:ListOfTypesDlk.index')->only('index');
    // $this->middleware('permission:ListOfTypesDlk.store')->only('store');
    // $this->middleware('permission:ListOfTypesDlk.show')->only('show');
    // $this->middleware('permission:ListOfTypesDlk.edit')->only('edit');
    // $this->middleware('permission:ListOfTypesDlk.update')->only('update');
    // $this->middleware('permission:ListOfTypesDlk.delete')->only('destroy');
    // }
    public function index()
    {
        //
      $data=  ListOfTypesDlkModel::all();
return response()->json(['data'=> $data],200);
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
    public function store(Request $request)
    {
        //
       $validateData=$request->validate([
        'name'=>'required|unique:list_of_types_dlk,name'
       ]);
       $data=ListOfTypesDlkModel::create($validateData);
    return  response()->json(['data'=> $data],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $data=  ListOfTypesDlkModel::find( $id);
        return   response()->json(['data'=> $data],200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $data=  ListOfTypesDlkModel::find( $id);
        return  response()->json(['data'=> $data],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $validateData=$request->validate([
            'name'=>'required|unique:list_of_types_dlk,name'
           ]);
           $data= ListOfTypesDlkModel::find($id);
           $data->update($validateData);
           return    response()->json(['data'=> $data],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $data=  ListOfTypesDlkModel::find( $id);
        $data->delete();
        return  response()->json(['data'=> $data],200);
    }
}
