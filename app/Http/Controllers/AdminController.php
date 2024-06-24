<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Traits\GeneralTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Random;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use App\Mail\EmailUser;
use App\Models\Admin;
use Illuminate\Support\Facades\Mail;
//use PSpell\Config;

class AdminController extends Controller
{
    use GeneralTrait;

    /*public function __construct()
    {
        config(['auth.defaults.guard'=>'admin-api']);

    }*/


    public function register (AuthRequest $request)
    {
        try {

            DB::beginTransaction();
            $num_Code = sprintf("%06d", mt_rand(1, 999999));
            $admin=Admin::create([
                'name'           => $request->name,
                'email'          => $request->email,
                'password'       => $request->password,
                'remember_token' => Str::random(60),
                'code'           =>$num_Code
            ]);
           //Mail::to($admin->email)->send(new EmailUser($admin->code));

            DB::commit();
            return $this->returnSuccessMessage('Registering successfully');

        } catch (\Exception $ex) {
            DB::rollback();
            return $this->returnError("", "Please try again later");
        }

    }

    public function login(LoginRequest $request)
    {
        try {

            $credentials = $request->only(['email', 'password']);
            $token = JWTAuth::attempt($credentials);
            $token=auth()->guard('admin-api')->attempt($credentials);
            //config(['auth.guards.api.provider'=>'admin']);

            if (!$token)
                return $this->returnError('E001', 'Unauthorized');

            $admin =auth()->guard('admin-api')->user();
            $admin->token = $token;

            //return $this->returnData('admin', $admin);
            return $this->createNewToken($token);

        } catch (\Exception $ex) {
            return $this->returnError("", "Please try again later");
        }

    }

    public function me()
    {
        return $this->returnData('user', auth()->guard('admin-api')->user());
    }

    /*public function logout(Request $request)
    {
        $token = $request->bearerToken();
        if ($token) {
            try {

                JWTAuth::setToken($token)->invalidate(); //logout
                return $this->returnSuccessMessage('Logged out successfully');
            } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                return $this->returnError('', 'some thing went wrongs');
            }
        } else {
            return $this->returnError('', 'some thing went wrongs');
        }
    }*/



    public function setcode(Request $request)
    {
        $this->validate($request,[
            'code'=>'required|string',
            'email'=>'email'
        ]);

        $code=$request->code;
        $admin=Admin::where('email','=',$request->email)->first();
        if($code == $admin->code)
        {
            $admin->active="true";
            $admin->save();
            return $this->returnSuccessMessage('true');
        }
        return  $this->returnError('', 'false');
    }

    protected function createNewToken($token)
    {
        return response()->json([
           'access_token'=>$token,
           'token_type'=>'bearer',
           'expires_in'=>strtotime(date('Y-m-d H:i:s',strtotime("+60 min"))),
           'user'=>auth()->guard('admin-api')->user()
        ]);
    }

    /*public function sendEmail(Request $request)
    {
        $this->validate($request,[
           'email'=>'required|email',
        ]);
        $response=$this->broker()->sendResetLink($request->only('email'));

        if ($response == Password::RESET_LINK_SENT)
        {
            return response()->json("password reset link sent");
        }
        else{
            return response()->json("unable to send reset link");
        }
    }*/


}
