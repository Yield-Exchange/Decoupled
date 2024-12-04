<?php

namespace App\Models;

use App\User;
use App\Models\BlogCategory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $table='blogs';
    protected $guarded = ['id'];

    protected $fillable=['tags','title','seo_title','seo_description','status','body','main_image','created_by','updated_by','modified_section','description','category_id'];

    protected $with=['createdBy','updatedBy','category'];

    protected $appends=['title_slug','slug_objects'];

    public function getTitleSlugAttribute()
    {
        return strtolower(str_replace(" ","-",$this->title));
    }

    public function getSlugObjectsAttribute()
    {
        if(!empty($this->tags)){
            try{
                return BlogTag::find(json_decode($this->tags));
            }catch(\Exception $e){}
        }

        return null;
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class,'updated_by');
    }

    public function published() {
        return $this->where('status','PUBLISHED');
    }

    public function category()
    {
        return $this->belongsTo(BlogCategory::class,'category_id');
    }
}
