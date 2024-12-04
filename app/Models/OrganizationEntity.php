<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationEntity extends Model
{
    use HasFactory;
    protected $fillable = [

        'organization_id', 'organization_name', 'organization_type',
        'incorporation_province', 'owns_over_twenty_five', 'percentage_ownership',
        'cra_business_number', 'inc_business_number', 'orperating_for_entity', 'relationship_with_entity', 'modified_section', 'deleted_at'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
