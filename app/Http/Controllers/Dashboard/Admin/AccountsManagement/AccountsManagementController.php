<?php

namespace App\Http\Controllers\Dashboard\Admin\AccountsManagement;

use App\CustomEncoder;
use App\Http\Controllers\Controller;
use App\Services\Admin\AcountsManagement\AccountsManagementService;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Arr;

class AccountsManagementController extends Controller
{

    protected $accountsmanagementservice;

    public function __construct(AccountsManagementService $accountsmanagementservice)
    {
        $this->accountsmanagementservice = $accountsmanagementservice;
    }

    public function respondOnAccessRequest(Request $request){


    return $this->accountsmanagementservice->respondOnAccessRequest($request);
    }
    public function getAllowedOrganizations(Request $request){


      return  $this->accountsmanagementservice->getAllowedOrganizations($request);
    }
}
