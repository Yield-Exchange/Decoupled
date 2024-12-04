<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;
    protected $table="users";

    public function Organizations(){
        return $this->belongsToMany(Organization::class,"users_organizations","user_id","organization_id")->withPivot("status");
    }
}
