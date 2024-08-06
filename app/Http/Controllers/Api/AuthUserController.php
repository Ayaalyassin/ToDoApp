<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\LoginRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use App\Traits\GeneralTrait;


class AuthUserController extends Controller
{
    use GeneralTrait;

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService=$userService;
    }

    public function register (AuthRequest $request)
    {
        $this->userService->register($request);
        return $this->returnSuccessMessage('Registering successfully');
    }

    public function login(LoginRequest $request)
    {
        $user=$this->userService->login($request);

        return $this->returnData('user', $user);

    }

    public function me()
    {
        return $this->returnData('user', auth()->user());
    }

    public function logout(Request $request)
    {
        $this->userService->logout($request);
        return $this->returnSuccessMessage('Logged out successfully');
    }

    public function setcode(Request $request)
    {
        $this->userService->setcode($request);
        return $this->returnSuccessMessage('true');
    }



}
