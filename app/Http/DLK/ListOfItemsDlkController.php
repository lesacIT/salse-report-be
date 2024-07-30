<?php

namespace App\Http\Controllers\DLK;

use App\Http\Controllers\Controller;
use App\Models\ListOfItemsDlkModel;
use Illuminate\Http\Request;

class ListOfItemsDlkController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function __construct()
    // {
    // $this->middleware('permission:ListOfItemsDlk.index')->only('index');
    // $this->middleware('permission:ListOfItemsDlk.store')->only('store');
    // $this->middleware('permission:ListOfItemsDlk.show')->only('show');
    // $this->middleware('permission:ListOfItemsDlk.edit')->only('edit');
    // $this->middleware('permission:ListOfItemsDlk.update')->only('update');
    // $this->middleware('permission:ListOfItemsDlk.delete')->only('destroy');
    // }
     
    public function index()
    {
        //
        $data=  ListOfItemsDlkModel::all();
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
            'name'=>'required|unique:list_of_items_dlk,name'
           ]);
        $data=   ListOfItemsDlkModel::create(  $validateData);
        return response()->json( $data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $data=  ListOfItemsDlkModel::find( $id);
        return response()->json( $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $data=  ListOfItemsDlkModel::find( $id);
        return response()->json( $data);
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
           $data= ListOfItemsDlkModel::find($id);
           $data->update($validateData);
           return response()->json( $data);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $data=  ListOfItemsDlkModel::find( $id);
        $data->delete();
        return response()->json( $data);
    }
}
