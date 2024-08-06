<?php


namespace App\Services;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService
{
    use GeneralTrait;
    public function register ($request)
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
            return $user;

        } catch (\Exception $ex) {
            DB::rollback();
            throw new HttpResponseException($this->returnError('', "Please try again later"));
        }

    }

    public function login(LoginRequest $request)
    {
        try {

            $credentials = $request->only(['email', 'password']);
            $token = JWTAuth::attempt($credentials);

            if (!$token)
                throw new HttpResponseException($this->returnError('E001', 'Unauthorized'));

            $user =auth()->user();
            $user->token = $token;

            return $user;

        } catch (\Exception $ex) {
            throw new HttpResponseException($this->returnError("", "Please try again later"));
        }

    }


    public function logout(Request $request)
    {
        $token = $request->bearerToken();
        if ($token) {
            try {

                JWTAuth::setToken($token)->invalidate(); //logout
            } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
                throw new HttpResponseException($this->returnError('', 'some thing went wrongs'));
            }
        } else {
            throw new HttpResponseException($this->returnError('', 'some thing went wrongs'));
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
        }
        throw new HttpResponseException($this->returnError('', 'false'));
    }

}
