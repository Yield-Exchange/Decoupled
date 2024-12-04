<?php

namespace App\Services\Admin\Blog;

use App\Constants;
use App\CustomEncoder;
use App\Models\Blog;
use App\Services\BaseService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BlogService extends BaseService{
    public function fetch(Request $request) : array
    {
        $user = \auth()->user();
        if(!$user->userCan('admin/blogs/page-access')){
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

        $data = Blog::select([
            'blogs.*'
        ])->whereNotIn('status',['DELETED']);

        $totalRecords = with(clone $data)->count();

        $search_columns = [
            "title",
            "status",
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
                $data = $data->where('blogs.created_at', 'like', '%' . $date_in_utc . '%')
                    ->orWhere('blogs.updated_at', 'like', '%' . $date_in_utc . '%');
                $search_is_date=true;
            }catch (\Exception $exception){}

            if (!$search_is_date) {
                $data = $data->where(function ($query) use ($searchValue, $search_columns, $data) {
                    $query->where('blogs.title', 'like', '%' . $searchValue . '%');
                    foreach ($search_columns as $search_column) {
                        switch ($search_column) {
                            case'status':
                                $query->orWhere('blogs.' . $search_column, 'like', '%' . $searchValue . '%');
                                break;
                        }
                    }
                });
            }
        }

        if(!$columnIndex && !is_numeric($columnIndex)){
            $data = $data->orderBy('blogs.id','DESC');
        }else{
            switch ($columnName){
                case'title':
                case'status':
                case'created_at':
                case'updated_at':
                    $data = $data->orderBy('blogs.'.$columnName,strtoupper($columnSortOrder));
                    break;
                default:
                    $data = $data->orderBy('blogs.id','DESC');
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

            $action='<div class="dropdown">
                        <button class="btn custom-primary round dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Actions
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">';

            $blog_id =  CustomEncoder::urlValueEncrypt($record['id']);

            if($user->userCan('admin/blogs/preview')) {
                $action .= ' <a class="dropdown-item" href="' . url('/blog/' . $record['id'] . "/" . $record['title_slug']) . '?from_admin=1">Preview</a> ';
            }
            if($user->userCan('admin/blogs/edit')) {
                $action .= ' <a class="dropdown-item" href="' . url('/yie-admin/blogs/create?action=edit&blog_id=' . $blog_id) . '">Edit</a> ';
            }

            if($user->userCan('admin/blogs/publish')) {
                if ($record->status != "PUBLISHED") {
                    $action .= ' <a class="dropdown-item admin-approve-blog" action="' . url('/yie-admin/blog/approve?action=approve&blog_id=' . $blog_id) . '" href="javascript:void()">Approve</a> ';
                }
            }
            if($user->userCan('admin/blogs/delete')) {
                $action .= ' <a class="dropdown-item admin-delete-blog" action="' . url('/yie-admin/blog/delete?action=delete&blog_id=' . $blog_id) . '" href="javascript:void()">Delete</a> ';
            }

            $action.='</div>';
            $action.='</div>';

            $status = $record['status'];
            switch ($status){
                case "DRAFT":
                    $status = "<span class='badge badge-warning'>".$status."</span>";
                    break;
                case "PENDING":
                    $status = "<span class='badge badge-info'>".$status."</span>";
                    break;
                case "PUBLISHED":
                    $status = "<span class='badge badge-success'>".$status."</span>";
                    break;
            }

            $data_arr[] = array(
                "sno" => $i++,
                "image"=>$record->main_image ? "<img src='".url('/blog/images/'.$record->main_image)."' style='width:100px' class='img-responsive' alt='".$record->title."' />" : "",
                "title" => Str::limit($record->title, 150, $end='...'),
                "category" => $record->category ? Str::limit($record->category->name, 150, $end='...') : '',
                "author"=>!empty($record->createdBy) ? $record->createdBy->name : '',
                "status" => $status,
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

    public function delete(Model $blog) : Model
    {
        $blog->status = "DELETED";
        $blog->modified_section = "Status update to DELETED";
        archiveTable($blog->id,'blogs', auth()->id(),'"Status update to DELETED"');
        $blog->save();

        return $blog;
    }

    public function store(array $data) : Model
    {
        $data['created_by'] = auth()->id();
        if(!empty($data['tags']) && is_array($data['tags'])){
            $data['tags'] = json_encode($data['tags']);
        }else{
            $data['tags'] = null;
        }
        
        return Blog::create($data);
    }

    public function update(array $data, Model $blog) : Model
    {
        // if(!empty($data['tags']) && is_array($data['tags'])){
        //     $data['tags'] = json_encode($data['tags']);
        // }
        // else{
        //     $data['tags'] = null;
        // }
        
        $data['updated_by'] = auth()->id();
        $blog->update($data);

        return $blog;
    }
}