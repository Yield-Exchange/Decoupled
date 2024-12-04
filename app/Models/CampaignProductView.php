<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignProductView extends Model
{
    use HasFactory;
    protected $fillable = ['campaign_f_i_campaign_product_id', 'viewer_organization_id', 'viewer_user_id', 'is_test'];
    public function campaignProduct()
    {
        $this->belongsTo(CampaignFICampaignProduct::class, 'campaign_product_id');
    }
}
