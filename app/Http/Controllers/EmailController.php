<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Mail\EmailUser;

class EmailController extends Controller
{
    public function send()
    {
        Mail::to(Auth::user()->email)->send(new EmailUser(Auth::user()->code));
        return response()->json("send success");
    }
}
