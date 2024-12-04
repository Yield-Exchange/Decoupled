<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use DB;

class BlogCategory extends Model
{
    protected $table='blog_categories';
    
    protected $guarded = ['id'];

    protected $fillable=['name','created_by','updated_by'];

    protected $with=['createdBy','updatedBy', 'blogs'];

    protected $appends=['name_slug'];

    public function getNameSlugAttribute()
    {
        return strtolower(str_replace(" ","-",$this->name));
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class,'updated_by');
    }

    public function blogs(){
        return $this->hasMany(Blog::class, 'id', 'category_id');
    }

    public static function categoryWithBlog() {
        $categoryWithBlog = DB::table('blogs')->select('category_id')->where('status', 'PUBLISHED')->distinct()->get();
        $categories = [];
        foreach($categoryWithBlog as $category) {
            array_push($categories, $category->category_id);
        }
        return self::whereIn('id', $categories)->get();
    }
}
