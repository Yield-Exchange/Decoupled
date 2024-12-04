<?php

namespace App\Services\Admin\Blog;

use App\Models\BlogAndTag;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogAndTagService extends BaseService{

    public function fetch(Request $request) {
        
    }

    public function store(array $data) : Model  {
         return BlogAndTag::create($data);
     }

     public function update(array $data, Model $blog) : Model
     {
    
        $tags = json_decode($blog->tags, true);
        foreach ($tags as $tag) {
            $blogTag = BlogAndTag::where('tag_id', $tag)->where('blog_id', $blog->id)->firstOrFail();
            $blogTag->update($data);
        }
        return $blog;

     }

     public function delete(Model $blog) : Model
     {
        $tags = json_decode($blog->tags, true);
        foreach ($tags as $tag) {

            $blogTag = BlogAndTag::where('tag_id', $tag)->where('blog_id', $blog->id)->firstOrFail();
            $blogTag->status = "DELETED";
            $blogTag->save();
        }
        return $blog;
     }
}