<?php

namespace App\Models;

use App\CustomEncoder;
use App\Traits\OrganizationsTraitsRelationShip;
use App\User;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrganizationLevelPermission;

class Organization extends Model
{
    use OrganizationsTraitsRelationShip;

    protected $table = 'organizations';
    protected $fillable = [
        'name',
        'logo',
        'type',
        'admin_user_id',
        'is_non_partnered_fi',
        'created_by',
        'is_temporary',
        'account_manager',
        'inviter_name',
        'status',
        'modified_section',
        'created_at',
        'updated_at',
        'is_test',
        'last_login',
        'users_limit',
        'naics_code_id',
        'potential_yearly_deposit_id',
        'wholesale_deposit_portfolio_id',
        'fi_type_id',
        'allow_multi_organizations',
        'requires_to_confirm_users_seats',
        'digital_account_opening',
        'accepted_market_place_offer',
        'visible_for_provinces',
        'visible_for_customers',
        'visible_for_naics_codes',
        "accepted_terms_and_conditions",
        "sign_up_from",
        'show_province_visibility',
        'show_naics_codes_visibility',
        'show_customers_visibility',
        "industry_id",
        "is_partially_approved",
        "needs_update",
        "login_count",
        "enable_campaigns",
        'is_waiting',
        'registration_type',
        'is_from_conference',
        'trade_name',
        'incoporation_number',
        'CRA_business_number',
        'incoporation_date',
        'provice_of_incorporation',
        "intended_use",
        "province_of_incorporation",
        "is_individual"

    ];

    protected $with = ['demographicData'];

    protected $appends = ['organization_users_limit', 'users_count', 'ratings', 'fi_type', 'naics_code', 'is_bank', 'is_depositor', 'encoded_id'];

    public function routing()
    {
        return $this->hasOne(AWSFileRouting::class);
    }

    public function getRatingsAttribute()
    {
        $depositCreditRating = $this->depositCreditRating;
        return [
            'credit_rating' => !empty($depositCreditRating->creditRating) ? $depositCreditRating->creditRating->description : "",
            'insurance_rating' => !empty($depositCreditRating->insuranceRating) ? $depositCreditRating->insuranceRating->description : "",
        ];
    }

    public function getInsuranceRatingAttribute()
    {
        return 3;
    }

    public function getFiTypeAttribute()
    {
        $type = FITypes::where('id', $this->fi_type_id)->first();
        return $type ? $type->description : '';
    }

    public function getNaicsCodeAttribute()
    {
        $naicscode = NAICS::where('id', $this->naics_code_id)->first();
        return $naicscode ? $naicscode->description : '';
    }

    public function getOrganizationUsersLimitAttribute()
    {
        return $this->users_limit;
    }

    public function getUsersCountAttribute()
    {
        return $this->users()->count();
    }

    public function getIsBankAttribute()
    {
        return ($this->type == 'BANK') ? true : false;
    }

    public function getIsDepositorAttribute()
    {
        return ($this->type == 'DEPOSITOR') ? true : false;
    }

    public function users($status = null)
    {
        $users = User::with(['demographicData'])->where('organizations.id', $this->id)
            ->join('users_organizations', 'users_organizations.user_id', '=', 'users.id')
            ->join('organizations', 'users_organizations.organization_id', '=', 'organizations.id')
            ->whereIn('account_status', systemActiveUsersStatuses());

        if ($status && in_array($status, systemActiveUsersStatuses())) {
            $users->where('account_status', $status);
        }
        return $users->select([
            'users.*'
        ])->groupBy('users.id')->get();
    }

    public function notifiableUsersEmails($return_emails = true)
    {
        if ($this->type == 'BANK' && $this->is_temporary == 1) {
            return [];
        }

        $usr = auth()->user();

        $active_user_status = systemActiveUsersStatuses();
        $active_user_status = array_diff($active_user_status, ['PENDING', 'SUSPENDED']); // remove pending users

        $users = $this->users();
        if ($usr && $usr->admin_loggedin_as) {
            $users = $users->merge(collect([ratesUser()]));
        }

        $emails = [];
        foreach ($users as $user) {
            if ($user->super_user || getUserPreference('mute_notification', $user->id) != 1 && in_array($user->account_status, $active_user_status)) {
                array_push($emails, $return_emails ? $user->email : $user);
            }
        }

        return $emails;
    }

    public function adminUsersEmails($exclude_admin_id = null)
    {
        $users = $this->users()->where('role_name', 'Organization Administrator');
        if (!empty($exclude_admin_id)) {
            $users = $users->where('id', '!=', $exclude_admin_id);
        }
        return $users->pluck('email')->toArray();
    }

    public function marketPlaceOffer()
    {
        return $this->hasMany(Campaign::class);
    }
    public function campaign()
    {
        return $this->hasMany(Campaign::class, 'fi_id');
    }
    public function FIgroups()
    {
        return $this->belongsToMany(FICampaignGroup::class)->withTimestamps()->withPivot('deleted_at');
    }

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }
    public function document()
    {
        return $this->hasMany(OrganizationDocument::class);
    }
    public function entities()
    {
        return $this->hasMany(OrganizationEntity::class);
    }
    public function keyIndividuals()
    {
        return $this->hasMany(OrganizationKeyIndividual::class);
    }
    public function getEncodedIdAttribute($value)
    {
        return CustomEncoder::urlValueEncrypt($this->id);
    }

    public function organizationHas($permissions)
    {
        $foundrecords = OrganizationLevelPermission::join("org_permissions_lists", "org_permissions_lists.id", "=", "organization_level_permissions.org_permissions_list_permission_id")
            ->where('organization_id', $this->id)
            ->where('status', 'Active')
            ->whereIn('org_permissions_lists.slug', (array) $permissions)
            ->get();

        return ($foundrecords->isNotEmpty()) ? true : false;
    }
    // public function CGTradeRequest(){
    //     return $this->hasMany(CGTradeRequest::class);
    // }
}
