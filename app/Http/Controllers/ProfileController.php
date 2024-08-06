<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Services\ProfileService;
use App\Traits\GeneralTrait;
use App\Traits\ImageTrait;

class ProfileController extends Controller
{
    use GeneralTrait,ImageTrait;

    protected $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService=$profileService;
    }

    public function index()
    {
        $profiles = $this->profileService->getAll();
        return $this->returnData('profiles', $profiles);
    }


    public function store(ProfileRequest $request)
    {
        $profile=$this->profileService->store($request);
         return $this->returnData('profile', $profile);

    }


    public function show($id)
    {
        $profile =$this->profileService->show($id);

        return $this->returnData('profile', $profile);
    }



    public function update(ProfileRequest $request)
    {
        $this->profileService->update($request);

        return $this->returnSuccessMessage('updated successfully');

    }


    public function destroy()
    {
        $this->profileService->destroy();
        return $this->returnSuccessMessage('delete successfully');
    }
}
