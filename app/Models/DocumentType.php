<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    use HasFactory;

    protected $fillable = ['id', 'type_name'];

    public function organizationDocument(){
        return $this->hasMany(OrganizationDocument::class);
    }
}
