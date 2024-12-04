<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use DB;
class OrganizationDocument extends Model
{
    use HasFactory;
    protected $fillable = ['organization_id', 'description', 'type_id', 'file_name','user_uploaded_file_name'];
    protected $appends = ['organization_name','document_type'];


    public function organization(){
        return $this->belongsTo(Organization::class);
    }
    public function documentType(){
        return $this->belongsTo(DocumentType::class);
    }
//    public function getDocumentTypeAttribute(){
//        return $this->documentType->type_name;
//    }
    public function getDocumentTypeAttribute(){
      $type=DB::table("document_types")->where("id",$this->type_id)->pluck("type_name");
      return (sizeof($type)>0)?$type[0]:null;
   }

    public function getOrganizationNameAttribute()
    {
        return $this->organization->name;
    }

}
