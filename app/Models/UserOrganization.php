<?php

namespace App\Models;

use App\Traits\OrganizationRelationShip;
use Illuminate\Database\Eloquent\Model;

class UserOrganization extends Model
{
    use OrganizationRelationShip;

    protected $table = 'users_organizations';
    protected $fillable = ['user_id', 'organization_id', 'status', 'switched_organization_type','is_default'];
}
