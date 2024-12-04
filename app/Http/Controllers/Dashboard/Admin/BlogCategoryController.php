<?php
namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;

use App\Services\Admin\Blog\BlogCategoryService;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\CustomEncoder;

class BlogCategoryController extends Controller
{
    private $blogCategoryService;
    public function __construct(BlogCategoryService $blogCategoryService)
    {
        $this->middleware('auth');
        $this->middleware('auth.admin');

        $this->blogCategoryService = $blogCategoryService;
    }

    public function store(Request $request){
        $user= auth()->user();
        if(!$user->is_super_admin){
            $response = array("success"=>false, "message"=>"Access denied", "data"=>[]);
            return response()->json($response, 403);
        }

        if( !$user->userCan('admin/blogs-categories/create') || !$user->userCan('admin/blogs-categories/edit')  ){
            $response = array("success"=>false, "message"=>"Permission denied", "data"=>[]);
            return response()->json($response, 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            $response = array("success"=>false, "message"=>Arr::flatten($validator->messages()->get('*')), "data"=>[]);
            return response()->json($response, 400);
        }

        $editing=false;
        if($request->has('action') && $request->action == "edit"){
            if($request->filled('blog_category_id')){
                $blog = BlogCategory::find($request->blog_category_id);
                if($blog){
                    $editing=true;
                }
            }
        }

        $data_to_update = [
            'name'=>$request->name,
        ];

        if($editing && isset($blog)){
            $data_to_update['updated_by'] = auth()->id();
            $blog = $this->blogCategoryService->update($data_to_update, $blog);
        }else{
            $data_to_update['created_by'] = auth()->id();
            $blog = $this->blogCategoryService->store($data_to_update);
        }

        $actioned = $editing ? "updated" : "created";

        $response = array("success"=>true, "message"=>"Blog ".$actioned." successfully", "data"=>$blog);
        return response()->json($response, 200);
    }

    public function create(Request $request){}

    public function index(Request $request)
    {
        $user= auth()->user();
        if( !$user->userCan('admin/blogs-categories/page-access') ){
            return view('dashboard.403');
        }

        systemActivities(Auth::id(), json_encode($request->query()), "Admin Access  > Blog Categories");
        return view('dashboard.admin.blog.categories.index');
    }

    public function deleteBlog(Request $request){
        $user= auth()->user();
        if( !$user->userCan('admin/blogs-categories/delete') ){
            return view('dashboard.403');
        }

        if($request->has('action') && $request->action == "delete"){
            if(!$request->has('blog_category_id')){
                return redirect()->back();
            }

            $blog = BlogCategory::find(CustomEncoder::urlValueDecrypt($request->blog_category_id));
            if(!$blog){
                return redirect()->back();
            }
            $this->blogCategoryService->delete($blog);
        }

        return redirect()->back();
    }

    public function getData(Request $request){
        $response = $this->blogCategoryService->fetch($request);
        return response()->json($response,200);
    }

}