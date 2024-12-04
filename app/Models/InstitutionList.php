<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstitutionList extends Model
{
    protected $table='fi_list';
    protected $guarded = ['id'];
    protected $fillable=['name','status'];

    public $timestamps = false;
}