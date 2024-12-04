<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\CustomEncoder;
use App\Http\Controllers\Controller;
use App\Models\Industry;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class IndustriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('auth.admin');
    }

    public function index(Request $request)
    {
        systemActivities(Auth::id(), json_encode($request->query()), "Admin Access  > Industries");
        return view('dashboard.admin.industries.index');
    }

    public function getData(Request $request){
        ## Read value
        $draw = $request->draw;
        $start = $request->filled('start') ? $request->start : 0;
        $rowperpage = $request->filled('start') ? $request->length: 15; // Rows display per page

        $columnIndex_arr = $request->order;
        $columnName_arr = $request->columns;
        $order_arr = $request->order;
        $search_arr = $request->search;

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value

        $data = Industry::query()
            ->select([
                '*',
            ]);

        $totalRecords = with(clone $data)->count();

        if( !empty($searchValue) ) {
            $search_is_date=false;

            if (!$search_is_date) {
                $data = $data->where(function ($query) use ($searchValue, $data) {
                    $query->where('name', 'like', '%' . $searchValue . '%');
                });
            }
        }

        if(!$columnIndex && !is_numeric($columnIndex)){
            $data = $data->orderBy('id','DESC');
        }else{
            switch ($columnName){
                case 'name':
                    $data = $data->orderBy('name',strtoupper($columnSortOrder));
                    break;
                default:
                    $data = $data->orderBy('id','DESC');
                    break;
            }
        }

        $totalRecordswithFilter = with(clone $data)->count();

        $data = $data->skip($start)
            ->take($rowperpage)
            ->get();

        $data_arr = array();

        $i=1;

        $user = \auth()->user();
        foreach($data as $record) {

            $action = "";
            if($user->userCan('admin/industries/edit')) {
                $action = '<div id="edit-industry-' . $record->id . '" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><b>Update Industry</b></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="" method="post" autocomplete="off" id="CreateIndustry">
                                <input type="hidden" name="industry_id" value="' . $record->id . '" />
                                <input type="hidden" name="action" value="edit" />
                                    ' . csrf_field() . '
                                    <div class="row">
                                        <div class="col-md-12 well">
                                            <h5>Category</h5>
                                            <div class="form-group">
                                                <input type="text" value="' . $record->name . '" id="industry" name="industry" placeholder="Enter Industry" maxlength="26" minlength="1"  class="form-control col-md-12" required/>
                                            </div>
                                        </div>
            
                                        <div class="row" align="center">
                                            <div class="col-md-12 well">
            
                                            <div class="form-group">
                                                <input type="button" class="btn custom-secondary round" data-dismiss="modal" value="Cancel">
                                                <input type="submit" class="btn custom-primary round mmy_btn CreateIndustrySubmitBtn" value="Submit" />
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer"></div>
                        </div>
                    </div>
                </div>
            </div>';
            }

            $action.='<div class="dropdown">
            <button class="btn custom-primary round dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Actions
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';

            $id =  CustomEncoder::urlValueEncrypt($record['id']);

            if($user->userCan('admin/industries/edit')) {
                $action .= ' <a class="dropdown-item" action="' . url('/yie-admin/industries/create?action=edit&industry_id=' . $id) . '" data-toggle="modal" data-target="#edit-industry-' . $record->id . '" href="javascript:void()">Edit</a> ';
            }
            if($user->userCan('admin/industries/delete')) {
                $action .= ' <a class="dropdown-item admin-delete-industry" action="' . url('/yie-admin/industries-delete?action=delete&industry_id=' . $id) . '" href="javascript:void()">Delete</a> ';
            }

            $action.='</div>';
            $action.='</div>';

            $data_arr[] = array(
                "sno" => $i++,
                "name" => $record->name,
                "action"=>$action
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        return response()->json($response,200);
    }

    public function store(Request $request){
        $user= auth()->user();
        if(!$user->is_super_admin){
            return view('dashboard.403');
        }

        $user = \auth()->user();
        if( !$user->userCan('admin/industries/create') && !$user->userCan('admin/industries/edit')  ){
            return view('dashboard.403');
        }

        $validator = Validator::make($request->all(), [
            'industry' => 'required'
        ]);

        if ($validator->fails()) {
            $response = array("success"=>false, "message"=>Arr::flatten($validator->messages()->get('*')), "data"=>[]);
            return response()->json($response, 400);
        }

        $editing=false;
        if($request->has('action') && $request->action == "edit"){
            if($request->filled('industry_id')){
                $industry = Industry::find($request->industry_id);
                if($industry){
                    $editing=true;
                }
            }
        }

        $data_to_update = [
            'name'=>$request->industry,
        ];

        if($editing && isset($industry)){
            $data_to_update['updated_by'] = auth()->id();
            $industry->update($data_to_update);
        }else{
            $data_to_update['created_by'] = auth()->id();
            $industry = Industry::create($data_to_update);
        }

        $response = array("success"=>true, "message"=>"Industry ".(!$editing? "Created" : "Updated")." successfully", "data"=>$industry);
        return response()->json($response, 200);
    }

    public function delete(Request $request){
        $user = \auth()->user();
        if( !$user->userCan('admin/industries/delete') ){
            alert()->error("Permission Denied");
            return redirect()->back();
        }

        if($request->has('action') && $request->action == "delete"){
            if(!$request->has('industry_id')){
                alert()->error("Industry Not Found");
                return redirect()->back();
            }

            $industry = Industry::find(CustomEncoder::urlValueDecrypt($request->industry_id));
            if(!$industry){
                alert()->error("Industry Not Found");
                return redirect()->back();
            }

            $industry->delete();
            systemActivities(Auth::id(), json_encode($request->query()), "Admin Access  > Deleted Industry");
        }

        alert()->success("Industry Deleted successfully");
        return redirect()->back();
    }
}
