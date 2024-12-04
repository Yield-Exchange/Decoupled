<?php
namespace App\Traits;

use App\Models\Organization;

trait OrganizationRelationShip{
    public function organization()
    {
        return $this->belongsTo(Organization::class,'organization_id');
    }
}