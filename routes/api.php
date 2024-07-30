<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\DLK\LinkPointListController;
use App\Http\Controllers\DLK\ListOfItemsDlkController;
use App\Http\Controllers\DLK\ListOfTypesDlkController;
use App\Http\Controllers\ProvinceCityController;
use App\Http\Controllers\Role\AddPermissionsController;
use App\Http\Controllers\Role\PermissionController;
use App\Http\Controllers\User\OrganizationController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\Target\BusinessGoalController;
use App\Http\Controllers\Target\CatDailyActivitieGroupsController;
use App\Http\Controllers\Target\CatDailyActivityController;
use App\Http\Controllers\Target\CatTimeSlotController;
use App\Http\Controllers\Target\DayReportController;
use App\Http\Controllers\Target\OperationalGoalController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Vehicle\AutomakerController;
use App\Http\Controllers\Vehicle\CapacityController;
use App\Http\Controllers\Vehicle\RangeOfVehicleController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password',[AuthController::class,'forgotPassword']);
Route::get('/password/reset/{token}', function ($token) {
    return response()->json(['token' => $token]);
})->name('password.reset');
// Route::resource('/role', RoleController::class);
// Route::resource('/operational-goal',OperationalGoalController::class);
// Route::resource('/activity', ActivityController::class);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::apiresource('/role', RoleController::class); //
    
    Route::apiresource('/operational-goal', OperationalGoalController::class);

    Route::apiresource('/category-daily-activity', CatDailyActivityController::class);//

    Route::apiresource('/category-daily-activity-group', CatDailyActivitieGroupsController::class);//

    Route::apiresource('/category-time-period', CatTimeSlotController::class);//

   
    Route::get('/current_user', [AuthController::class,'current_user']);

    Route::resource('/business-goals', BusinessGoalController::class);
    
    Route::resource('/day-report', DayReportController::class);

    Route::apiresource('/organization', OrganizationController::class);
    Route::get('/user-of-organization', [OrganizationController::class,'UserOfOrganization']);

    
    Route::apiresource('/user', UserController::class);
    Route::get('/createUser', [UserController::class,'createUser']);
    Route::get('/organization/user/{id}', [UserController::class,'getUsersByGroup']);
    Route::post('/active/user/{id}', [UserController::class,'getActiveUser']);


    Route::post('/reset-password', [UserController::class,'resetPassword']);
    Route::post('/change-role', [UserController::class,'ChangeRole']);
    Route::get('/user-activities', [UserController::class,'userActivities']);
    Route::delete('/user-activities/{id}', [UserController::class,'userDeleteActivities']);

    Route::get('/search-user', [UserController::class,'searchUser']);


    Route::get('/free-organization', [OrganizationController::class,'GetFreeOrganization']);
    Route::resource('/permission', PermissionController::class);

   
    Route::resource('/category', CategoryController::class);

    Route::resource('/automaker', AutomakerController::class);
    Route::resource('/range-of-vehicle', RangeOfVehicleController::class);
    Route::resource('/capacity', CapacityController::class);
    Route::get('price-vehicle',[AutomakerController::class,'PriceVehicle']);


    Route::get('add-role',[AddPermissionsController::class,'usersHaveRoles']);
    Route::post('add-role',[AddPermissionsController::class,'postusersHaveRoles']);
    Route::delete('add-role/{id}',[AddPermissionsController::class,'deleteusersHaveRoles']);
    Route::get('role-user',[AddPermissionsController::class,'RoleshaveUsers']);
    Route::get('add-permission',[AddPermissionsController::class,'RolesHavePermission']);
    Route::post('add-permission',[AddPermissionsController::class,'postRolesHavePermission']);
    Route::delete('add-permission/{id}',[AddPermissionsController::class,'deleteRolesHavePermission']);

    Route::resource('link_point_list',LinkPointListController::class);
    Route::resource('list_of_items_dlk',ListOfItemsDlkController::class);
    Route::resource('list_of_types_dlk',ListOfTypesDlkController::class);


    Route::get('commission-calculation',[LinkPointListController::class,'CommissionCalculation']);

    Route::get('province-city',[ProvinceCityController::class,'index']);


});
// Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');