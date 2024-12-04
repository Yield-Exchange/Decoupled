<?php
namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\BlogAndTag;

use App\Services\Admin\Blog\BlogService;
use App\Services\Admin\Blog\BlogAndTagService;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\CustomEncoder;
use App\Models\BlogCategory;
use App\Models\BlogTag;

class BlogController extends Controller
{
    private $blogService;
    private $blogTagService;
    public function __construct(BlogService $blogService, BlogAndTagService $blogTagService)
    {
        $this->middleware('auth');
        $this->middleware('auth.admin');

        $this->blogService  = $blogService;
        $this->blogTagService  = $blogTagService;
    }

    public function store(Request $request){
        $user= auth()->user();
        if(!$user->is_super_admin){
            return view('dashboard.403');
        }

        $user = \auth()->user();
        if( !$user->userCan('admin/blogs/create') && !$user->userCan('admin/blogs/edit')  ){
            return view('dashboard.403');
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',
            'content' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            $response = array("success"=>false, "message"=>Arr::flatten($validator->messages()->get('*')), "data"=>[]);
            return response()->json($response, 400);
        }

        $editing=false;
        if($request->has('action') && $request->action == "edit"){
            if($request->filled('blog_id')){
                $blog = Blog::find($request->blog_id);
                if($blog){
                    $editing=true;
                }
            }
        }
        // dd($request->tags);
        $data_to_update = [
            'title'=>$request->title,
            'description'=>$request->description,
            'body'=>$request->get('content'),
            'status'=>$request->status,
            'seo_title'=>$request->seo_title,
            'seo_description'=>$request->seo_description,
            'tags'=>$request->tags,
            'category_id'=>$request->category_id
        ];

        if($request->hasFile('image')){
            $file = $request->file('image');
            $destinationPath = public_path() . '/blog/images/';
            $file_name = time()."-".str_replace(" ","_",$request->title).'.png';
            if($file->move($destinationPath,$file_name)){
                $data_to_update['main_image'] = $file_name;
            }
        }

        if(!$user->userCan('admin/blogs/publish')){
            $data_to_update['status'] = strtoupper(trim($data_to_update['status'])) == 'PUBLISHED' ? 'PENDING' : $data_to_update['status'];
        }

        if($editing && isset($blog)){
            $data_to_update['updated_by'] = auth()->id();
            archiveTable($blog->id,'blogs', auth()->id(),'General Blog Update');
            $blog = $this->blogService->update($data_to_update, $blog);
        }else{
            $data_to_update['created_by'] = auth()->id();
            $blog = $this->blogService->store($data_to_update);
        }
        
        foreach  ($data_to_update['tags'] as $tag) {
            BlogAndTag::updateOrCreate(['blog_id' => $blog->id, 'tag_id' => $tag],[
                'tag_id' => $tag,
                'blog_id' => $blog->id,
                'status' => $blog->status,
            ]);
        }
        $actioned = $editing ? "updated" : "created";

        $blog_id =  CustomEncoder::urlValueEncrypt($blog->id);
        $url = url('/yie-admin/blogs/create?action=edit&blog_id='.$blog_id);
        systemActivities(Auth::id(), json_encode($request->query()), "Admin Access  > $actioned blog");
        $response = array("success"=>true, "message"=>"Blog ".$actioned." successfully", "data"=>[], "url"=>$url);
        return response()->json($response, 200);
    }

    public function create(Request $request){
        $user = \auth()->user();
        if(!$user->is_super_admin){
            return view('dashboard.403');
        }

        if( !$user->userCan('admin/blogs/create') && !$user->userCan('admin/blogs/edit')  ){
            return view('dashboard.403');
        }

        $blog=null;
        if($request->has('action') && $request->action == "edit"){
            if(!$request->has('blog_id')){
                return redirect()->back();
            }

            $blog = Blog::find(CustomEncoder::urlValueDecrypt($request->blog_id));
            if(!$blog){
                return redirect()->back();
            }
        }

        $categories = BlogCategory::all();
        $tags = BlogTag::all();
        if(!$user->userCan('admin/blogs/publish')){
            $status = ['DRAFT', 'PENDING'];
        }else {
            $status = ['DRAFT', 'PUBLISHED', 'PENDING'];
        }
        systemActivities(Auth::id(), json_encode($request->query()), "Admin Access  > Create Blog");
        return view('dashboard.admin.blog.new',compact('blog','categories','tags','status'));
    }

    public function index(Request $request)
    {
        $user = \auth()->user();
        if( !$user->userCan('admin/blogs/page-access') ){
            return view('dashboard.403');
        }

        systemActivities(Auth::id(), json_encode($request->query()), "Admin Access  > Blog");
        return view('dashboard.admin.blog.index');
    }

    public function deleteBlog(Request $request){
        $user = \auth()->user();
        if( !$user->userCan('admin/blogs/delete') ){
            return view('dashboard.403');
        }

        if($request->has('action') && $request->action == "delete"){
            if(!$request->has('blog_id')){
                return redirect()->back();
            }

            $blog = Blog::find(CustomEncoder::urlValueDecrypt($request->blog_id));
            if(!$blog){
                return redirect()->back();
            }
//            $tags = json_decode($blog->tags, true);

            $this->blogService->delete($blog);
            $this->blogTagService->delete($blog);

            systemActivities(Auth::id(), json_encode($request->query()), "Admin Access  > Deleted Blog");
        }

        return redirect()->back();
    }

    public function getData(Request $request){
        $response = $this->blogService->fetch($request);
        return response()->json($response,200);
    }

    public function uploadBlogImage(Request $request){ 
        // dd($request->hasFile('image'));
        if($request->hasFile('image')){
            $file = $request->file('image');
            $destinationPath = public_path() . '/blog/images/';
            $file_name = time().'.png';
            if($file->move($destinationPath,$file_name)){
                $profile_image = $file_name;
                // dd(url('/blog/images/'.$file_name));
                $response = ['url' => url('/blog/images/'.$file_name)];
                return response()->json($response,200);
            }
        }
        return null;
    }

    public function approve(Request $request){
        $user = \auth()->user();
        if( !$user->userCan('admin/blogs/publish') ){
            return view('dashboard.403');
        }

        if($request->has('action') && $request->action == "approve"){
            if(!$request->has('blog_id')){
                return redirect()->back();
            }

            $blog = Blog::find(CustomEncoder::urlValueDecrypt($request->blog_id));
            if(!$blog){
                return redirect()->back();
            }
            $data = [
                'status'=>'PUBLISHED'
            ];

            archiveTable($blog->id,'blogs', auth()->id(),'Status Updated to PUBLISHED');
            $this->blogService->update($data, $blog);
            $this->blogTagService->update($data,  $blog);
            systemActivities(Auth::id(), json_encode($request->query()), "Admin Access  > PUBLISHED Blog");
        }

        return redirect()->back();
    }

}