<?php

namespace App\Http\Controllers\Dashboard\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductsController extends Controller
{
    public function index() {
        return view('dashboard.admin.products.index');
    }


    public function get_products() {


        $requests = Product::orderBy('description', 'ASC');
         
        $totalRecords = with(clone $requests)->get()->count();
        
        $data = $requests;

        $searchValue = request('search');
        $getColumns = explode(',', request('columns'));
        
        if( !empty($searchValue) ) {
            $data = $requests->where(function ($query) use ($searchValue, $getColumns, $requests) {

                foreach ($getColumns as $search_column) {
                    switch ($search_column) {
                        case'disabled':
                            $query->orWhere('products.is_disabled', 'like', '%' . $searchValue . '%');
                            break;
                        case 'disabled_till':
                            $query->orWhere('products.desable_until', 'like', '%' . $searchValue . '%');
                            break;
                        default:
                            $query->orWhere('products.description', 'like', '%' . $searchValue . '%');
                            break;
                    }
                }
            });
        }

        $sortOrder = request('sortOrder');
        $sortColumn = request('sortColumn');

        if(!$sortColumn && !$sortOrder){
            $data = $data->orderBy('products.id','DESC');
        }else{
                switch ($sortColumn) {
                    case'description':
                        $data = $data->orderBy('products.description', strtoupper($sortOrder));
                        break;
                    case 'disabled_till':
                        $data = $data->orderBy('products.desable_until', strtoupper($sortOrder));
                        break;
                    case 'disabled':
                        $data = $data->orderBy('products.is_disabled', strtoupper($sortOrder));
                        break;
                    }
        }

        $start =  request('startQueryFrom');
        $rowperpage = request('rowParPage');
        $totalRecords = with(clone  $requests)->get()->count();
        $totalRecordswithFilter = with(clone $data)->get()->count();

        $data = $data->skip($start)
            ->take($rowperpage)
            ->get();
        
        $data_arr = [];
        $i = 1;
        foreach ($data as $record) {

            $status = $record->is_disabled;
            switch ($status){
                case "yes":
                    $status = "<span class='badge badge-warning'>Disabled</span>";
                    break;
                case "no":
                    $status = "<span class='badge badge-success'>Active</span>";
                    break;
                default:
                    
            }
            
            $data_arr[] = array(
                "description" =>  $record->description,
                "product_id" =>  $record->id,
                "id" =>  $i++,
                "disabled" => $status,
                "disabled_status" =>  $record->is_disabled,
                "activationDate" =>  $record->activationDate,
                "deactivationDate" =>  $record->deactivationDate,
                "disabled_till" => ($record->disabled_till) ? $record->disabled_till : "",
            );

         }
    


        $response = array(
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        return response()->json($response);
    }

    public function add_product() {
        
        request()->validate([
            'description' => "required|string",
            'activationDate' => "nullable",
            'deactivationDate' => "nullable",
        ]);
        $description = Str::lower(request('description'));
        $deactivationDate = request('deactivationDate') ? request('deactivationDate') : "";
        $activationDate = request('activationDate') ? request('activationDate') : "";
       
        if(request('id')) {
            $product = Product::where('id', request('id'))->first();
            $product->description = $description;
            $product->deactivationDate = $deactivationDate;
            $product->activationDate = $activationDate;
            $product->save();
            return response()->json(['status'=>true, 'message'=>"product updated successfully"], 200);
        }

        if (Product::where('description', $description)->first()) {
            return response()->json(['status'=>false, 'message'=>"Failed to create existing product"], 409);
        }

        
        request()->validate([
            'status' => "required|string",
        ]);
        $status = request('status') == "activate" ? "no" : "yes";

        Product::create([
            "description" => $description,
            "is_disabled" => $status,
            "deactivationDate" => $deactivationDate,
            "activationDate" => $activationDate,
        ]);
        return response()->json(['status'=>true, 'message'=>"product created successfully"], 200);
    }

    public function toggle_status() {
        request()->validate([
            'id' => "required",
            'status' => "required",
        ]);
        $product = Product::where('id', request('id'))->first();
        if (!$product) {
            return response()->json(['status'=>false, 'message'=>"Failed to find product"], 404);
        }
        $product->is_disabled = request('status');
        $product->activationDate = "";
        $product->deactivationDate = "";
        $product->save();
        return response()->json(['status'=>true, 'message'=>"product updated successfully"], 200);
    }

}
