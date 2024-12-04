<?php

namespace App\Models;

use App\CustomEncoder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CGTradeRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'source',
        'trade_product_id',
        'copied_from_id',
        'bulk_reference_no',
        'currency',
        'request_status',
        'reference_no',
        'organization_id',
        'user_id',
        'is_test',
        'is_demo',
        'modified_date',
        'modified_section',
        'admin_loggedin_as',
    ];
    protected $appends=['encoded_id'];
    public function CGTradeRequestInvitedCT(){
        return $this->hasMany(CGTradeRequestInvitedCT::class);
    }
    public function getEncodedIdAttribute($value)
    {
        return CustomEncoder::urlValueEncrypt($this->id);
    }
    public function CG(){
        return $this->belongsTo(Organization::class, 'organization_id')
        ->select(['id', 'name','logo'])
        ->with(['demographicData' => function ($query) {
            $query->select(['id', 'organization_id', 'org_bio','website']);
        }]);
    }
    
}
