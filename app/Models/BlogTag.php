<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use DB;

class BlogTag extends Model
{
    protected $table='blog_tags';
    protected $guarded = ['id'];

    protected $fillable=['name','created_by','updated_by'];

    protected $with=['createdBy','updatedBy'];

    protected $appends=['name_slug'];

    public $tags = [];

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

    public function blogs() {
        return $this->belongsToMany(Blog::class, 'blog_and_tags', 'tag_id', 'blog_id');
    }

    public static function tagWithBlog() {
        $tagsWithBlog = DB::table('blog_and_tags')->select('tag_id')->where('status', 'PUBLISHED')->distinct()->get();
        $tags = [];
        foreach($tagsWithBlog as $tag) {
            array_push($tags, $tag->tag_id);
        }
        return self::whereIn('id', $tags)->get();
    }
}
