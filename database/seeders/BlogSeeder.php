<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Factory;
use DB;
use App\Models\Blog;
use App\Models\BlogTag;
use App\Models\BlogAndTag;
use App\Models\BlogCategory;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        $tags = ['cats', 'dogs', 'fish', 'house', 'finance', 'business', 'loyal', 'bunce', 'classical', 'application'];
        BlogTag::truncate();

        foreach ($tags as $tag) {
            BlogTag::create([
                "name" => $tag,
                "created_by" => '1'
            ]);
        }
        
        $categoies = ['phone', 'animal', 'vacation', 'music', 'video', 'relax', 'joke', 'software'];
        BlogCategory::truncate();

        foreach ($categoies as $category) {
            BlogCategory::create([
                "name" => $category,
                "created_by" => '1'
            ]);
        }
        BlogAndTag::truncate();
        foreach (range(1, 50) as $blog) {
            BlogAndTag::create([
                'tag_id' => random_int(1, count($tags)),
                'blog_id' => random_int(1, 20),
            ]);
        }

        Blog::truncate();
        foreach (range(1, 20) as $blog) {
            $tags = DB::table('blog_and_tags')->select('tag_id')->distinct()->where('blog_id', $blog)->get('tag_id')->toArray();
            $btags = "[";
            foreach ($tags as $tag) {
                $btags .= "$tag->tag_id"; 
                if (end($tags) != $tag) {
                    $btags .= ",";
                }
            }
            $btags .= "]";
            Blog::create([
                'title' => $faker->text(50),
                'description'=>$faker->paragraph(),
                'body'=>"<p>". $faker->text()."</p>",
                'status'=> "PUBLISHED",
                'tags'=> "$btags",
                'category_id' => random_int(1, count($categoies)), 
                'created_by' => '1', 
                'main_image' => '1660992603-Test.png',
            ]);
        }


        

    }
}
