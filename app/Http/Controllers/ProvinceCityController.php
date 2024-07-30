<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProvinceResource;
use App\Http\Resources\WardResource;
use App\Models\LocalProvinceModel;
use App\Models\LocalWardModel;
use Illuminate\Http\Request;

class ProvinceCityController extends Controller
{
    //

    public function index(){
      $data=  LocalProvinceModel::get();
      $data->load('districts.wards');
      return response()->json(['data'=>ProvinceResource::collection($data)],200);
    }
}
