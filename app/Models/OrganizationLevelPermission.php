<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationLevelPermission extends Model
{
    use HasFactory;
    protected $fillable=['organization_id','org_permissions_list_permission_id','status'];
}
