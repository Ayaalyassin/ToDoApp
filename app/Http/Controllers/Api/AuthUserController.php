<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
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
use Illuminate\Support\Facades\Mail;

class AuthUserController extends Controller
{
    use GeneralTrait;
    public function register (AuthRequest $request)
    {
        try {

            DB::beginTransaction();
            $num_Code = sprintf("%06d", mt_rand(1, 999999));
            $user=User::create([
                'name'           => $request->name,
                'email'          => $request->email,
                'password'       => $request->password,
                'remember_token' => Str::random(60),
                'code'           =>$num_Code
            ]);

           //Mail::to($user->email)->send(new EmailUser($user->code));

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

            if (!$token)
                return $this->returnError('E001', 'Unauthorized');

            $user =auth()->user();
            $user->token = $token;

            return $this->returnData('user', $user);

        } catch (\Exception $ex) {
            return $this->returnError("", "Please try again later");
        }

    }

    public function me()
    {
        return $this->returnData('user', auth()->user());
    }

    public function logout(Request $request)
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
    }

    public function setcode(Request $request)
    {
        $this->validate($request,[
            'code'=>'required|string',
            'email'=>'email'
        ]);

        $code=$request->code;
        $user=User::where('email','=',$request->email)->first();
        if($code == $user->code)
        {
            $user->active="true";
            $user->save();
            return $this->returnSuccessMessage('true');
        }
        return  $this->returnError('', 'false');
    }



}
