<?php

namespace App\Services\Admin\AcountsManagement;

use App\CustomEncoder;
use App\Models\OrganizationLevelPermission;
use App\Models\OrgRequestAccess;
use App\Models\TradeProduct;
use App\Models\TradeBasketType;
use App\Models\TradeCollateral;
use App\Models\TradeCollateralBasket;
use Illuminate\Http\Request;
use App\Traits\UserTrait;
use DB;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Validator;

class AccountsManagementService
{

    public function respondOnAccessRequest($request)
    {
        $requestdetails = $request->all();
        $requestdetails['requestId'] = CustomEncoder::urlValueDecrypt($request->requestId);

        $rules = [
            'action' => 'required|in:approve,decline',
            'requestId' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!OrgRequestAccess::where('id', $value)->exists()) {
                        $fail('The selected requestId is invalid.');
                    }
                }
            ]
        ];

        $validator = Validator::make($requestdetails, $rules);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        try {
            DB::beginTransaction();
            $foundRecord = OrgRequestAccess::where("id", $requestdetails['requestId'])->first();
            if ($foundRecord == null) {
                return response()->json([
                    'success' => true,
                    'message' => 'Request not found.'
                ], 404);
            }

            switch ($request->action) {
                case 'approve':
                    $permlevel = [
                        'organization_id' => $foundRecord->organization_id,
                        'org_permissions_list_permission_id' =>  $foundRecord->org_permissions_list_id,
                    ];
                    if (OrganizationLevelPermission::where($permlevel)->exists()) {
                        OrganizationLevelPermission::where($permlevel)->update(['status' => 'Active']);
                    } else {
                        OrganizationLevelPermission::create(array_merge($permlevel, ['status' => 'Active']));
                    }

                    $permlevel2 = [
                        'organization_id' => $foundRecord->organization_id,
                        'org_permissions_list_id' =>  $foundRecord->org_permissions_list_id,
                    ];
                    OrgRequestAccess::where($permlevel2)->update(['status' => "APPROVED"]);
                    break;
                case 'decline':

                    $permlevel = [
                        'organization_id' => $foundRecord->organization_id,
                        'org_permissions_list_permission_id' =>  $foundRecord->org_permissions_list_id,
                    ];

                    if (OrganizationLevelPermission::where($permlevel)->exists()) {
                        OrganizationLevelPermission::where($permlevel)->update(['status' => 'Inactive']);
                    }
                    $permlevel2 = [
                        'organization_id' => $foundRecord->organization_id,
                        'org_permissions_list_id' =>  $foundRecord->org_permissions_list_id,
                    ];
                    OrgRequestAccess::where($permlevel2)->update(['status' => "DECLINED"]);
                    break;
                default:
                    throw new \Exception('Invalid action.');
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Request processed successfully.'
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error processing request: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Request was not processed successfully.',
                'error' => 'Failed to process the request.'
            ], 500);
        }
    }
    public function getAllowedOrganizations(Request $request)
    {
        return OrgRequestAccess::with(['user','organization','permissionDetails'])->paginate(($request->perPage)?$request->perPage:10);
    }
}
