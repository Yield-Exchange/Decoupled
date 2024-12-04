<?php

namespace App\Models;

use App\Constants;
use App\CustomEncoder;
use App\Traits\OrganizationRelationShip;
use App\User;
use Illuminate\Database\Eloquent\Model;

class DepositRequest extends BaseModel
{
    use OrganizationRelationShip;

    protected $table = 'depositor_requests';
    protected $guarded = ['id'];
    protected $fillable = [
        'reference_no',
        'term_length_type',
        'term_length',
        'lockout_period_days',
        'lockout_period_months',
        'closing_date_time',
        'amount',
        'currency',
        'date_of_deposit',
        'compound_frequency',
        'requested_rate',
        'requested_short_term_credit_rating',
        'requested_deposit_insurance',
        'special_instructions',
        'request_status',
        'created_date',
        'closed_date',
        'user_id',
        'product_id',
        'modified_date',
        'modified_section',
        'modified_by',
        'bulk_reference_no',
        'organization_id',
        'is_test',
        'campaign_product_id',
        'admin_loggedin_as'
    ];

    public $timestamps = false;

    protected $appends = ['product_name', 'request_encypted_id', 'product_description', 'product_definition'];
    protected $hidden = ['product'];

    protected $with = ['user', 'inviter', 'invitinguser'];

    protected static function boot()
    {
        parent::boot();

        DepositRequest::creating(function ($model) {
            $model->is_test = auth()->user()->is_test;
        });
    }

    public function getRequestEncyptedIdAttribute()
    {
        return CustomEncoder::urlValueEncrypt($this->id);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function customUser()
    {
        if ($this->admin_loggedin_as) {
            return $this->customRelation(ratesUser());
        }

        return $this->user();
    }

    public function invited()
    {
        return $this->hasMany(InvitedBank::class, 'depositor_request_id')->with("bank");
    }
    public function inviter()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
    public function invitinguser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getProductNameAttribute()
    {
        return !empty($this->product) ? $this->product->description : '';
    }
    public function getProductDescriptionAttribute()
    {
        return !empty($this->product) ? $this->product->definition : '';
    }

    public function getProductDefinitionAttribute()
    {
        return !empty($this->product) ? $this->product->definition : '';
    }

    public function offers()
    {
        return $this->hasManyThrough(Offer::class, InvitedBank::class, 'depositor_request_id', 'invitation_id');
    }

    public function MarketPlaceOffer()
    {
        return $this->belongsTo(Campaign::class, 'market_place_offer_id');
    }

    public function count_viewed_invited()
    {
        return $this->invited()->where('seen', "yes")->count();
    }
}
