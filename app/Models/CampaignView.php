<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignView extends Model
{
    use HasFactory;
    protected $fillable = ['campaign_id', 'viewer_organization_id', 'viewer_user_id', 'is_test'];
}
