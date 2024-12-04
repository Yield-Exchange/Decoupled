<?php

namespace App\Models;

use App\CustomEncoder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;


class TradeProduct extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_name',
        'is_disabled',
        'disabled_until',
        'description',
        'filter_key'
    ];
   protected $appends=['encoded_id'];

    protected function serializeDate(\DateTimeInterface $date)
    {
        return changeDateFromLocalToUTC($date->format('Y-m-d H:i:s'));
    }
    public function getDisabledUntilAttribute($value)
    {
        return changeDateFromLocalToUTC($value);
    }
    public function getEncodedIdAttribute($value)
    {
        return CustomEncoder::urlValueEncrypt($this->id);
    }
}
