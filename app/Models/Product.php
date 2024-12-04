<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'products';
    protected $guarded = ['id'];
    protected $fillable = [
        'description', 'definition', 'earning_rate', 'earning_text', 'flexibility_rate',
        'flexibility_text', 'is_disabled', 'activationDate', 'deactivationDate'
    ];
    public function FICampaignProduct()
    {
        return $this->hasMany(FICampaignProduct::class, "product_type_id");
    }
}
