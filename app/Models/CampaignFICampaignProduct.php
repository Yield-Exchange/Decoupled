<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Constants;
use DateTime;

class CampaignFICampaignProduct extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'fi_campaign_product_id',
        'rate_type',
        'index_rate',
        'spread',
        'rate',
        'minimum',
        'maximum',
        'order_limit',
        'campaign_id',
        'deleted_at',
        'isFeatured',
        'status'
    ];
    protected $appends = [
        'product_purchases_total',
        'product_purchases_count',
        'unique_product_views',
        'unique_product_views_count',
        'campaign_product_views_count',
        'today_campaign_product_clicks_count'
    ];
    public function campaign()
    {
        return $this->belongsTo(Campaign::class, "campaign_id")->with("campaignGroups");
    }
    public function product()
    {
        return $this->belongsTo(FICampaignProduct::class, "fi_campaign_product_id")->whereNull("deleted_at")->with("productType");
    }
    public function clicks()
    {
        return $this->hasMany(CampaignProductView::class, "campaign_product_id");
    }
    public function depositRequests()
    {
        return $this->hasMany(DepositRequest::class, 'campaign_product_id');
    }
    public function offers()
    {
        return $this->hasMany(Offer::class, 'campaign_product_id');
    }
    public function getProductPurchasesCountAttribute()
    {
        return   $this->depositRequests()->count();
    }
    public function getProductPurchasesTotalAttribute()
    {
        return   $this->depositRequests()->sum("amount");
    }
    public function campaignProductViews()
    {
        return $this->hasMany(CampaignProductView::class, 'campaign_f_i_campaign_product_id');
    }
    public function getUniqueProductViewsAttribute()
    {
        return $this->campaignProductViews()->distinct("viewer_organization_id")->pluck("viewer_organization_id")->toArray();
    }
    public function getUniqueProductViewsCountAttribute()
    {
        return $this->campaignProductViews()->distinct("viewer_organization_id")->count();
    }
    public function getCampaignProductViewsCountAttribute()
    {
        return $this->campaignProductViews()->count();
    }
    public function getTodayCampaignProductClicksCountAttribute()
    {
        $today = date("Y-m-d");
        return $this->campaignProductViews()->whereDate("created_at", changeDateFromLocalToUTC($today, Constants::DATE_TIME_FORMAT))->count();
    }

}
