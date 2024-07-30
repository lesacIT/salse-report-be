<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;


use Illuminate\Support\Facades\Mail;

use App\Mail\ResetPaswordMail;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Support\Str;
// use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller
{
    public function login(Request $request)
    {

        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'remember' => 'boolean'
        ]);

        $remember = $request->boolean('remember', false);
        $user = User::where('username', $request->username)->first();

        // Check if the user exists and the password is correct
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }


        if ($remember) {
            $accessToken = $user->createToken('auth_token', ['*'], now()->addDays(30))->plainTextToken;
        } else {
            $accessToken = $user->createToken('auth_token', ['*'], now()->addHours(2))->plainTextToken;
        }


        // check devices when login
     // Analyze user agent
    // $agent = new Agent();
    // $agent->setUserAgent($request->header('User-Agent'));

    // $device = [
    //     'device' => $agent->device(),
    //     'platform' => $agent->platform(),
    //     'browser' => $agent->browser(),
    // ];
    $a=$request->header('User-Agent');
    $ip=$request->ip();

        return response()->json([
            'access_token' => $accessToken,
            'token_type' => 'Bearer',
        ]);
    }


    public function forgotPassword(Request $request)
    {
        // Validate email
        // $request->validate([
        //     'email' => 'required|email',
        // ]);

        // $status = Password::sendResetLink(
        //     $request->only('email')
        // );

        // if ($status === Password::RESET_LINK_SENT) {

        //     return response()->json(['message' => 'Email reset password '], 200);
        // } else {
        //     // Error occurred
        //     return response()->json(['message' => 'error'], 500);
        // } $request->validate(['email' => 'required|email']);

        $email = $request->input('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $token = Hash::make(Str::random(60));

       $checkReset= PasswordReset::where('email',$email)->first();

       if($checkReset){
        $checkReset->update(['token' =>  $token,'created_at'=>now()]);
       }
       else{
        PasswordReset::insert([
            'email' => $email,
            'token' =>  $token,
            'created_at'=>now(),
        ]);
       }

        $content = [
            'subject' => 'Password Reset Request',
            'body' => 'You are receiving this email because we received a password reset request for your account.',
            'reset_link' => url("/password/reset/{$token}")
        ];

        Mail::to($request->only('email'))->send(new ResetPaswordMail($content));


        return response()->json(['massage'=>"Email has been sent.",'token'=>$token]) ;

    }
    public function current_user()
    {
        $user=Auth::user();

      $user->load('organization');
      $user->load('roles');
$data= new UserResource( $user);
      return response()->json(['data'=>$data]);
    }
}

