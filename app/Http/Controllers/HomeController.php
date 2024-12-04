<?php
namespace App\Http\Controllers;

use App\Mail\ContactUsMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogTag;
use App\Models\BlogAndTag;
class HomeController extends Controller
{
    public function index(){
        return view('home.index');
    }

    public function aboutUs(){
        return view('home.about-us');
    }

    public function contactSubmit(Request $request){
        exit();
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'phone' => 'required',
            'name' => 'required',
            'message' => 'required'
        ]);

        if ( $validator->fails() ) {
            $response = array("success"=>false, "message"=>$validator->errors(), "data"=>[]);
            return response()->json($response, 400);
        }

    
        if ( !verifyCaptcha($request->recaptcha) ){
            $response = array("success"=>false, "message"=>"Invalid recaptcha", "data"=>[]);
            return response()->json($response, 400);
        }

        $message = '<p>';
        $message .= "<strong>Name:</strong> " . $request->name;
        $message .= "<br/><strong>Email:</strong> " . $request->email;
        $message .= "<br/><strong>Phone:</strong> " . $request->phone;
        $message .= "<br/><strong>Message:</strong> " . $request->message;
        $message .= "</p>";

        Mail::to(getAdminEmails())->send(new ContactUsMail([
            'message_'=>$message,
        ]));

        return response()->json(["message"=>"Request sent successfully","data"=>[],"success"=>true],200);
    }

    public function blogs(Request $request, $filter_by=null, $id=null){
        $blogs = Blog::where('status','PUBLISHED');
        if(!empty($filter_by) && !empty($id)){
            if($filter_by=="tag"){
                $blogs = $blogs->whereJsonContains('tags',$id);      
            }else if($filter_by=="category"){
                $blogs = $blogs->where('category_id', $id);
            }
        }
        $blogs = $blogs->orderBy('id','DESC')->paginate(10);

        $tags = BlogTag::tagWithBlog();
        $categories = BlogCategory::categoryWithBlog();

        return view('home.blog-listing', compact('blogs','categories','tags'));
    }

    public function blogsWithTagsAndCategory(Request $request){

        $blog_from_category = [];
        $blog_from_tags = [];

        if (request('category')) {
            $category_id = request('category');
            $category_blog = Blog::orderBy('id','DESC')->where('status','PUBLISHED')->where('category_id', $category_id)->get()->toArray();
            $blog_from_category = [...$category_blog];
        }
        
        if (request('tags')) {
            $tag_list = explode(',', request('tags'));
            $get_blog_ids = BlogAndTag::whereIn('tag_id', $tag_list)->distinct()->get('blog_id');

            $blog_list_ids = [];
            foreach($get_blog_ids as $blog_id){
                array_push($blog_list_ids, $blog_id->blog_id);
            }

            $model = Blog::whereIn('id', $blog_list_ids)->where('status','PUBLISHED')->orderBy('id','DESC');

            if (request('category')) {
                $model->where('category_id', '!=', request('category'));
            }

            $blog_from_tags = $model->where('status','PUBLISHED')->orderBy('id','DESC')->get()->toArray();
        }


        $filtered_blogs = array_merge($blog_from_category, $blog_from_tags);
        
        if(count($filtered_blogs) < 1) {
            $filtered_blogs =  Blog::orderBy('id','DESC')->where('status','PUBLISHED')->paginate(10);
        }

        return response()->json(["blogs" => $filtered_blogs ], 202);
    }


    public function blogDetail(Request $request, $id, $slug){
        $blog = Blog::find($id);
        if(!$blog || !$request->filled('from_admin') && $blog->status!='PUBLISHED'){
            return redirect()->back();
        }

        $tags = BlogTag::tagWithBlog();
        $categories = BlogCategory::categoryWithBlog();
        $meta_description = $blog->seo_description;
        return view('home.blog-detail', compact('blog','categories','tags','meta_description'));
    }
}
