<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AWSFileRouting extends Model
{
    use HasFactory;
    protected $fillable = [
        'organization_id',
        'file_type',
        'routing_gent',
        'delivery_method'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
