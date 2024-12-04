<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Campaign extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'campaigns';
    protected $gurded = ['id'];
    protected $fillable = [
        'campaign_name',
        'expiry_date',
        'start_date',
        'currency',
        'status',
        'fi_id',
        'created_by',
        'subscription_amount',
        'description',
        'campaign_depositors_invite_type'
    ];

    protected $appends = ['current_order_amount', 'campaign_depositor_count','campaign_invite_depositors'];

    public function getCurrentOrderAmountAttribute()
    {
        return Deposit::select([
            'deposits.offered_amount',
        ])->join('offers', 'offers.id', '=', 'deposits.offer_id')
            ->join('campaign_f_i_campaign_products', 'campaign_f_i_campaign_products.id', '=', 'offers.campaign_product_id')
            ->join('campaigns', 'campaigns.id', '=', 'campaign_f_i_campaign_products.campaign_id')
            ->where('campaigns.id', $this->id)
            ->sum('offered_amount');
    }

    public function getStartDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }

    public function getExpiryDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d H:i');
    }

    public function getCampaignDepositorCountAttribute()
    {
        $counts = DB::table("f_i_campaign_group_depositors")
        ->join("campaign_target_groups", "campaign_target_groups.fi_campaign_group_id", "=", "f_i_campaign_group_depositors.fi_campaign_group_id")
        ->join("organizations", "organizations.id", "=", "f_i_campaign_group_depositors.depositor_id")
        ->where("campaign_target_groups.campaign_id", $this->id)
        ->whereNull("campaign_target_groups.deleted_at");
    
    if(auth()->check()) {
        $counts->where('is_test', auth()->user()->is_test);
    }
    
    $count = $counts->where('enable_campaigns', true)
        ->whereIn('status',['ACTIVE'])
        ->count();
    
    return ['invitees' => $count];
    }


    public function campaignGroups()
    {
        return $this->hasMany(FICampaignTargetGroup::class, 'campaign_id');
    }
    public function getCampaignInviteDepositorsAttribute()
    {
        $list = DB::table("f_i_campaign_group_depositors")
        ->join("campaign_target_groups", "campaign_target_groups.fi_campaign_group_id", "=", "f_i_campaign_group_depositors.fi_campaign_group_id")
        ->join("organizations", "organizations.id", "=", "f_i_campaign_group_depositors.depositor_id")
        ->where("campaign_target_groups.campaign_id", $this->id)
        ->whereNull("campaign_target_groups.deleted_at");
    
    if(auth()->check()) {
        $list->where('is_test', auth()->user()->is_test);
    }
    
    $list->where('enable_campaigns', true)
        ->whereIn('status',['ACTIVE'])
        ->select(DB::raw("DISTINCT(f_i_campaign_group_depositors.depositor_id) as organization_id"), 'organizations.name');
    
    $result = $list->get(); // Execute the query
    
    return $result;
        
    }
    // public function activeCampaignProducts()
    // {
    //     return CampaignFICampaignProduct::join("f_i_campaign_products", "f_i_campaign_products.id", "=", "campaign_f_i_campaign_products.fi_campaign_product_id")
    //         ->whereNull("f_i_campaign_products.deleted_at");
    // }
    public function campaignProducts()
    {
        return $this->hasMany(CampaignFICampaignProduct::class)->with("product");
    }

    public function campaignFI()
    {
        return $this->belongsTo(Organization::class, 'fi_id');
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'fi_id');
    }

}
