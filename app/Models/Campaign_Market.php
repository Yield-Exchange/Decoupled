<?php

namespace App\Models;

use App\Constants;
use App\Traits\OrganizationRelationShip;
use Illuminate\Database\Eloquent\Model;
use App\CustomEncoder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;

class Campaign_Market extends Model
{
    use OrganizationRelationShip, SoftDeletes;

    protected $table='market_place_offers';
    protected $guarded = ['id'];
    protected $fillable=['is_featured','reference_no','term_length_type','term_length','product_id','lockout_period','minimum_amount','maximum_amount',
        'compound_frequency','interest_paid','organization_id','created_by','modified_by','modified_section','is_test','status','interest_rate','currency',
        'rate_held_until', 'cumulative_total', 'product_disclosure_statement', 'product_disclosure_url'];

    protected $appends = ['product_name', 'encoded_id','expiry_date_formatted','interest_earned'];
    protected $hidden = ['product'];
    protected $with = ['organization'];

    protected static function boot()
    {
        parent::boot();

        Campaign_Market::creating(function($model) {
            $model->is_test = auth()->user()->is_test;
        });
    }

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    public function getProductNameAttribute()
    {
        return !empty($this->product) ? $this->product->description : '';
    }

    public function getExpiryDateFormattedAttribute()
    {
        return changeDateFromUTCtoLocal($this->rate_held_until,'F d, Y', Constants::DATE_FORMAT);
    }
    
    public function getEncodedIdAttribute()
    {
        return CustomEncoder::urlValueEncrypt($this->id);
    }
    public static function IsFeatured(){
        return self::where('is_featured',true)->where('status','ACTIVE');
    }

    public function remove_featured() {
        $this->update(['is_featured' => false]);
    }

    public function make_featured() {
        $this->update(['is_featured' => true]);
    }

    public function getInterestEarnedAttribute()
    {
        if(request()->filled('amount')&&request()->amount > 0) {
            return calculate_interest_earned(request()->amount, $this);
        }

        return calculate_interest_earned(1000000, $this);
    }
}
