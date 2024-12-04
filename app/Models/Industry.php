<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
    use HasFactory;
    protected $table ="industries";
    protected $fillable = ['created_by','updated_by','name'];
    public function organizations(){
        return $this->hasMany(Organization::class,);
    }
}
