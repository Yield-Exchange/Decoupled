<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FICampaignProduct extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "f_i_campaign_products";
    protected $fillable = [
        'product_type_id',
        'term_length_type',
        'term_length',
        'lockout_period',
        'compound_frequency',
        'default_product_name',
        'custom_product_name',
        'fi_id',
        'created_by',
        'interest_paid',
        'pds',
        'deleted_at',
        'status'
    ];
    public function campainFICampaignProducts()
    {
        return $this->hasMany(CampaignFICampaignProduct::class)->whereNull("deleted_at");
    }
    public function productType()
    {
        return $this->belongsTo(Product::class, "product_type_id");
    }
}
