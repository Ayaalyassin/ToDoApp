<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Permission;
use App\Models\User;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];


    public function permissions(){
        //return $this->belongsToMany(Permission::class,'role_permissions','role_id','permission_id');
        return $this->belongsToMany(Permission::class,'role_permissions');//,'permission_id','role_id');
    }

    public function users(){
        return $this->belongsToMany(User::class,'user_roles','user_id','role_id');
    }


}
