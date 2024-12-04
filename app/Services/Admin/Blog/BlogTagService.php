<?php

namespace App\Services\Admin\Blog;

use App\Constants;
use App\CustomEncoder;
use App\Models\BlogTag;
use App\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogTagService extends BaseService{
     public function fetch(Request $request)
     {
         $user = \auth()->user();
         if(!$user->userCan('admin/blogs-tags/page-access')){
             return array(
                 "draw" => 0,
                 "iTotalRecords" => 0,
                 "iTotalDisplayRecords" => 0,
                 "aaData" => []
             );
         }

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

         $data = BlogTag::select([
             '*'
         ]);

         $totalRecords = with(clone $data)->count();

         $search_columns = [
             "name",
             "created_at",
             "updated_at",
         ];

         if( !empty($searchValue) ) {
             $search_is_date=false;
             try {
                 try{
                     $date = Carbon::createFromFormat(Constants::DATE_TIME_FORMAT_NO_SECONDS, trim($searchValue));
                     $date_in_utc = changeDateFromLocalToUTC($date->format("Y-m-d"), Constants::DATE_FORMAT,Constants::DATE_FORMAT);
                 }catch (\Exception $exception) {
                     $date = Carbon::createFromFormat(Constants::DATE_FORMAT, trim($searchValue));
                     $date_in_utc = changeDateFromLocalToUTC($date->format("Y-m-d"), Constants::DATE_FORMAT,Constants::DATE_FORMAT);
                 }
                 $data = $data->where('created_at', 'like', '%' . $date_in_utc . '%')
                     ->orWhere('updated_at', 'like', '%' . $date_in_utc . '%');
                 $search_is_date=true;
             }catch (\Exception $exception){}

             if (!$search_is_date) {
                 $data = $data->where(function ($query) use ($searchValue, $search_columns, $data) {
                     $query->where('name', 'like', '%' . $searchValue . '%');
                 });
             }
         }

         if(!$columnIndex && !is_numeric($columnIndex)){
             $data = $data->orderBy('id','DESC');
         }else{
             switch ($columnName){
                 case'name':
                 case'created_at':
                 case'updated_at':
                     $data = $data->orderBy($columnName,strtoupper($columnSortOrder));
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
         foreach($data as $record) {

             $action = '';
             if($user->userCan('admin/blogs-tags/edit')) {
                 $action = '<div id="edit-blog-tag-' . $record->id . '" class="modal fade" role="dialog">
                    <div class="modal-dialog modal-sm">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title"><b>Update Blog Tag</b></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="" method="post" autocomplete="off" id="CreateCategory">
                                <input type="hidden" name="blog_tag_id" value="' . $record->id . '" />
                                <input type="hidden" name="action" value="edit" />
                                    ' . csrf_field() . '
                                    <div class="row">
                                        <div class="col-md-12 well">
                                            <h5>Tag</h5>
                                            <div class="form-group">
                                                <input type="text" value="' . $record->name . '" id="name" name="name" placeholder="Enter Tag" maxlength="26" minlength="1"  class="form-control col-md-12" required/>
                                            </div>
                                        </div>
            
                                        <div class="row" align="center">
                                            <div class="col-md-12 well">
            
                                            <div class="form-group">
                                                <input type="button" class="btn custom-secondary" data-dismiss="modal" value="Cancel">
                                                <input type="submit" class="btn custom-primary round mmy_btn CreateCategorySubmitBtn" value="Submit" />
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

             $blog_id =  CustomEncoder::urlValueEncrypt($record['id']);

             if($user->userCan('admin/blogs-tags/edit')) {
                 $action .= ' <a class="dropdown-item" action="' . url('/yie-admin/blogs-tag/create?action=edit&blog_tag_id=' . $blog_id) . '" data-toggle="modal" data-target="#edit-blog-tag-' . $record->id . '" href="javascript:void()">Edit</a> ';
             }
             if($user->userCan('admin/blogs-tags/delete')) {
                 $action .= ' <a class="dropdown-item admin-delete-blog-tag" action="' . url('/yie-admin/blogs-tag-delete?action=delete&blog_tag_id=' . $blog_id) . '" href="javascript:void()">Delete</a> ';
             }

             $action.='</div>';
             $action.='</div>';

             $data_arr[] = array(
                 "sno" => $i++,
                 "name" => Str::limit($record->name, 150, $end='...'),
                 "created_by"=>!empty($record->createdBy) ? $record->createdBy->name : '',
                 "created_at" => changeDateFromLocalToUTC($record->created_at, Constants::DATE_TIME_FORMAT,Constants::DATE_TIME_FORMAT),
                 "updated_at" => changeDateFromLocalToUTC($record->updated_at, Constants::DATE_TIME_FORMAT,Constants::DATE_TIME_FORMAT),
                 "action" => $action
             );
         }

         return array(
             "draw" => intval($draw),
             "iTotalRecords" => $totalRecords,
             "iTotalDisplayRecords" => $totalRecordswithFilter,
             "aaData" => $data_arr
         );
     }

     public function store(array $data) : Model
     {
         $data['created_by'] = auth()->id();
         return BlogTag::create($data);
     }

     public function update(array $data, Model $blogTag) : Model
     {
         $data['updated_by'] = auth()->id();
         $blogTag->update($data);

         return $blogTag;
     }

     public function delete(Model $blogTag) : Model
     {
         $blogTag->delete();
         return $blogTag;
     }
}