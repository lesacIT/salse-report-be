<?php

namespace App\Http\Controllers\DLK;

use App\Http\Controllers\Controller;
use App\Http\Requests\ListOfItemsDlkRequests;
use App\Models\LinkPointListModel;
use App\Models\ListOfItemsDlkModel;
use App\Models\ListOfTypesDlkModel;
use App\Models\OrganizationModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LinkPointListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
    // $this->middleware('permission:LinkPointList.index')->only('index');
    // $this->middleware('permission:LinkPointList.create')->only('create');
    // $this->middleware('permission:LinkPointList.store')->only('store');
    // $this->middleware('permission:LinkPointList.show')->only('show');
    // $this->middleware('permission:LinkPointList.edit')->only('edit');
    // $this->middleware('permission:LinkPointList.update')->only('update');
    // $this->middleware('permission:LinkPointList.delete')->only('destroy');
    }

    public function index()
    {
        $organization = OrganizationModel::where('code', 'like', '%SM%')
        ->orderBy('level', 'desc')
        ->first();
       $linkpointlist= LinkPointListModel::with('belong')->get()->toArray();
       $organization1 = OrganizationModel::max('level');
       $data=[];
     $loop =$organization1- $organization->level;
        foreach(  $linkpointlist as   $linkpoint){
          
            $a=[];
            $user=User::find( $linkpoint['belong']['manager_id'])->toArray();
            $b=$user;
            for($i=0;$i<$loop;$i++){
                $a=User::find(   $b['manager_id']);
              if($a==null){
               break;
              }
              $a->toArray();
              $b=$a;
              
            }
            $c=User::with('manager')->find($b['id'])->toArray();
            
            $data[] = [
                'linkpoint' => $linkpoint, 
                'user' =>  $c
            ];
          
        }
    // dd( $data); 
        return response()->json(['data' =>     $data], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $listofitem=ListOfItemsDlkModel::get();
        $listoftypes=ListOfTypesDlkModel::get();
        return response()->json(['listofitem'=>$listofitem, 'listoftypes'=>$listoftypes],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ListOfItemsDlkRequests $request)
    {
        //
        $userData=$request->all();
        $userData['user_id']=Auth::id();
        if ($request->hasFile('images')) {
           
            // Truy cập đến tệp ảnh
            $avatarPath = $request->file('images');
    
            // Xác định phần mở rộng và tạo tên mới cho tệp ảnh để tránh trùng lặp
            $imageName = uniqid().'.'.$avatarPath->getClientOriginalExtension();
    
            // Lưu trữ tệp ảnh trong thư mục lưu trữ
            $directory = 'public/images/dlk';
            $storedImagePath = $avatarPath->storeAs($directory, $imageName);
            $a =  'images/dlk/'. $imageName ;
            
            // Gán vào mảng dữ liệu người dùng
            $userData['image'] =  $a;
            // Gắn đường dẫn tới hình ảnh vào mảng dữ liệu người dùng
          
           
        }


        
     $data= LinkPointListModel::create( $userData);
    
     return response()->json(['data'=>$data],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $data=   LinkPointListModel::find($id);
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $data=   LinkPointListModel::find($id);
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       
  
        $data=   LinkPointListModel::find($id);
       $user= Auth::user();
        $userData=$request->all();
       
  //$userData['user_id']= $user->id;
        if ($request->hasFile('images')) {
            if ( $data->image) {
                Storage::delete($data->image);
            }
          
            // Truy cập đến tệp ảnh
            $avatarPath = $request->file('images');
          
            // Xác định phần mở rộng và tạo tên mới cho tệp ảnh để tránh trùng lặp
            $imageName = uniqid().'.'.$avatarPath->getClientOriginalExtension();
         
            // Lưu trữ tệp ảnh trong thư mục lưu trữ
            $directory = 'public/images/dlk';
            $storedImagePath = $avatarPath->storeAs($directory, $imageName);

            // Nối hai chuỗi lại với nhau
            $a =  'images/dlk/'. $imageName ;
            
            // Gán vào mảng dữ liệu người dùng
            $userData['image'] = $a;
          
        }      
       
        $data->update($userData);
        
        return response()->json(['massage'=>'update','data'=> $data],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
{
    $data = LinkPointListModel::find($id);

    if (!$data) {
        return response()->json(['message' => 'Record not found'], 404);
    }

    $data->delete();

    return response()->json(['message' => 'Record deleted successfully', 'data' => $data]);
}
    public function CommissionCalculation($moneyPL, $moneyXS,$moneyBANCA,$CRCCardNumber,$percent)
    {
        //
        $data=   $moneyPL+ $moneyXS+$moneyBANCA+$CRCCardNumber+$percent;
        return response()->json($data);
    }
    



}
