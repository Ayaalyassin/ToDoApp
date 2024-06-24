<?php

namespace App\Http\Controllers;

use App\Models\ResetCodePassword;
use Illuminate\Http\Request;
use App\Models\User;
use App\Mail\EmailUser;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use App\Events\RealTimeMessage;


class ResetCodePasswordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    }

    /**
     * Display the specified resource.
     */
    public function show(ResetCodePassword $resetCodePassword)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ResetCodePassword $resetCodePassword)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ResetCodePassword $resetCodePassword)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ResetCodePassword $resetCodePassword)
    {
        //
    }

    public function forgotpassword(Request $request)
    {
        $data=$request->validate([
            'email'=>'required|email|exists:users,email'
        ]);

        ResetCodePassword::where('email',$request->email)->delete();
        $data['code']=mt_rand(0,99999);
        $data['date']=

        $codedata=ResetCodePassword::create($data);

        Mail::to($request->email)->send(new EmailUser($codedata->code));

        return response(['message'=>trans('passwords.sent')],200);

    }

    public function CodeCheck(Request $request)
    {
        $request->validate([
            'code'=>'required|string|exists:reset_code_passwords,code'
        ]);

        $passwordReset=ResetCodePassword::firstWhere('code',$request->code);

        /*if($passwordReset->created_at > now()->addHour())
        {
            $passwordReset->delete();
            return response(['message'=>trans('passwords.code_is_expire')],422);
        }*/
        return response([
            'code'=>$passwordReset->code,
            'message'=>trans('passwords.code_is_valid')
        ],200);
    }


    public function passwordset(Request $request)
    {
        //$date=now()->addHour();
        //$date=now();
        $currentDatetime=Carbon::now();// التاريخ والوقت
        //$date=Carbon::parse($currentDatetime)->format('Y-m-d');//التاريخ
        //$date=Carbon::parse($currentDatetime)->format('H:m:s');

        $date=$currentDatetime->addHours(1);

        //$current_time=Date::now()->format('H:I:s');
        //return response()->json($current_time);
        //$currentDatetime=now();// التاريخ والوقت
        //$date=Carbon::parse($currentDatetime)->format('H:i:s'); //التاريخ
        //$date=$date()->addHours(1);



        return response()->json($date);

        $request->validate([
            'code'=>'required|string|exists:reset_code_passwords,code',
            'password'=>'required|string|min:6',
            //'re_password'=>'required|string|min:6|confirmed:password'
        ]);

        $passwordReset=ResetCodePassword::firstWhere('code',$request->code);

        $date=now()->addHour();

        /*if($passwordReset->created_at > now()->addHour())
        {
            $passwordReset->delete();
            return response(['message'=>trans('passwords.code_is_expire')],422);
        }
        */

        $user=User::firstwhere('email',$passwordReset->email);

        $user->update($request->only('password'));

        $passwordReset->delete();

        return response(['message'=>'password has been successfully reset'],200);

    }

    public function testemail()
    {
        //$code="Are you angry with like on facebook";
        $code="why you put your account on whatsApp";
        //Mail::to("ayasinaya97@gmail.com")->send(new EmailUser($code));
        //Mail::to("mohammadanouali@gmail.com")->send(new EmailUser($code));

        event(new RealTimeMessage('Hello World! I am an event 😄'));
        return response()->json('success');

    }
}
