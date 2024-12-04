<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationDemoGraphicData extends Model
{
    // use OrganizationRelationShip;

    protected $table = 'demographic_organization_data';
    protected $guarded = ['id'];
    protected $fillable = ['user_id',
        'address1',
        'address2',
        'city',
        'province',
        'postal_code',
        'website',
        'timezone',
        'telephone',
        'updated_at',
        'created_at',
        'organization_id',
        'email',
        'year_of_establishment',
        'value_of_assets',
        'short_term_DBRS_rating_id',
        'org_email',
        'org_bio',
        'description'
    ];

    public function getTimezoneAttribute()
    {
        // $admin = $this->organization->admin;
        return /*$admin ? $admin->timezone :*/'central';
    }
}
