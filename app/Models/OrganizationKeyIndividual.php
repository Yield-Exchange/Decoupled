<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationKeyIndividual extends Model
{
    use HasFactory;
    protected $fillable = [
        'organization_id', 'user_id', 'first_name', 'last_name', 'job_title',
        'is_director', 'owns_over_twenty_five', 'percentage_ownership', 'is_signingofficer',
        'is_politicallyexposed', 'is_actingonattorneypower', 'orperating_for_entity', 'operating_for_corporation',
        'relationship_with_corporation', 'modified_section', 'deleted_at'
    ];
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
