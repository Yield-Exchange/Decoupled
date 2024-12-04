<?php

namespace App\Services;

use App\Models\DepositCreditRating;
use App\Models\FITypes;
use App\Models\Organization;
use App\Models\OrganizationDemoGraphicData;
use App\Traits\FileUploadTrait;
use DB;
use Illuminate\Http\Request;
use App\Models\OrganizationDocument;

class SettingsService
{
    use FileUploadTrait;
    public function updateOrganizationGeneralInfo(Request $request)
    {
        try {
            DB::beginTransaction();
            $logo = "";
            $saveorggeninfo = [];
            if ($request->hasFile("logo")) {
                $allowedTypes = ['jpeg,jpg,png'];
                $maxSize = 5000000;
                $uploadedFile = $request->file('logo');
                $folder = "image";
                $filePath = $this->uploadFile($uploadedFile, $folder, $allowedTypes, $maxSize, $request->institution_name);
                if ($filePath['status'] === 1) {
                    $logo = $filePath['path'];
                    $saveorggeninfo['logo'] = $logo;
                }
    
            }
            //get FI id
            $fi = FITypes::where("description", $request->org_type)->first();
            //get FI id
    
            $saveorggeninfo['name'] = $request->institution_name;
            $saveorggeninfo['fi_type_id'] = $request->institution_type;
            $orgbefore = Organization::where("id",$request->org)->first()->toArray();
            $org = Organization::findOrFail($request->org);
            $org->update($saveorggeninfo);
            $orgafter = Organization::where("id",$request->org)->first()->toArray();

            ksort($orgbefore);
            ksort($orgafter);

    
            // Check if organization general information was updated
            $orgGeneralInfomatch = ($orgbefore==$orgafter);
    
            $savedemoorg['no_of_branches'] = $request->no_of_branches;
            $savedemoorg['province'] = $request->province;
            $savedemoorg['year_of_establishment'] = $request->yr_of_establishment;
            $savedemoorg['city'] = $request->city;

            $orgdemobefore=OrganizationDemoGraphicData::where("organization_id", $request->org)->first()->toArray();

            $orgdemo =OrganizationDemoGraphicData::where("organization_id", $request->org)->first();
            $orgdemo->update($savedemoorg);

            $orgdemoafter=OrganizationDemoGraphicData::where("organization_id", $request->org)->first()->toArray();
            // Check if organization demographic data was updated
            ksort($orgdemobefore);
            ksort($orgdemoafter);
            $orgDemoDatamatch = ($orgdemobefore==$orgdemoafter);
            $recordschanged=false;
            if(($orgDemoDatamatch&&$orgGeneralInfomatch)==false){
                $recordschanged=true;
            } else if(($orgDemoDatamatch&&$orgGeneralInfomatch)==true) {
                $recordschanged=false;
            }
            
            
            $thisorg = Organization::with(['WholeSaleDepositsPortfolio', 'NAICSCode', 'PotentialYearlyDeposit', 'demographicData', 'depositCreditRating'])->find($request->org);
            DB::commit();
    
            return response()->json(['success' => true, 'message' => 'General Information Updated Successfully', 'thisorg' => $thisorg,'geninfor'=> $orgGeneralInfomatch,'Orgde'=>$orgDemoDatamatch,'somerecordschanged'=> $recordschanged]);
        } catch (Exception $exp) {
            return response()->json(['success' => false, 'message' => 'Updated Failed.' . $exp->getMessage()]);
        }
    
    }
    
    public function updateOrganizationContactInfo(Request $request)
    {
        try {
        DB::beginTransaction();

        $contactinfobefore = OrganizationDemoGraphicData::where("organization_id", $request->org)
        ->select("address1","address2","org_email","email","postal_code","telephone","website")
        ->first()->toArray();

        $savedemoorg['address1'] = $request->address_line_1;
        $savedemoorg['address2'] = $request->address_line_2;
        $savedemoorg['org_email'] = $request->institution_email;
        $savedemoorg['email'] = $request->institution_email;
        $savedemoorg['postal_code'] = $request->postal_code;
        $savedemoorg['telephone'] = $request->telephone;
        $savedemoorg['website'] = $request->website;
   
        OrganizationDemoGraphicData::where("organization_id", $request->org)->update($savedemoorg);
        $thisorg = Organization::with(['WholeSaleDepositsPortfolio', 'NAICSCode', 'PotentialYearlyDeposit', 'demographicData', 'depositCreditRating'])->find($request->org);
        $contactinfoafter = OrganizationDemoGraphicData::where($contactinfobefore)->first();
        // ksort($contactinfobefore);
        // ksort($contactinfoafter);

        DB::commit();
        return response()->json(['success' => true,
         'message' => 'Contact Information Updated Successfully', 
         'thisorg' => $thisorg,
         'somerecordschanged'=>($contactinfoafter==null)?true:false
        ]);
    } catch (Exception $exp) {
        return response()->json(['success' => false, 'message' => 'Updated Failed.' . $exp->getMessage()]);
    }

    }
    public function updateAdditionalInfo(Request $request)
    {
        try {
            DB::beginTransaction();
    
            // Retrieve data before update
            $orgBefore = Organization::where("id", $request->org)->first()->toArray();
            $creditRatingBefore = (DepositCreditRating::where("organization_id", $request->org)->first())?DepositCreditRating::where("organization_id", $request->org)->first()->toArray():null;
            $orgDemoBefore = (OrganizationDemoGraphicData::where("organization_id", $request->org)->first())?OrganizationDemoGraphicData::where("organization_id", $request->org)->first()->toArray():null;
    
            // Update credit rating
            $savecreditrating = [
                'credit_rating_type_id' => $request->credit_rating,
                'deposit_insurance_id' => $request->deposit_insurance
            ];    
            if (DepositCreditRating::where("organization_id", $request->org)->exists()) {
                DepositCreditRating::where("organization_id", $request->org)->update($savecreditrating);
            } else {
                $savecreditrating['organization_id'] = $request->org;
                DepositCreditRating::create($savecreditrating);
            }    
            // Update organization general information
            $orgBefore = Organization::where("id",$request->org)->first()->toArray();

            $org = Organization::findOrFail($request->org);
            $org->update([
                'digital_account_opening' => $request->digital_account_opening,
                'wholesale_deposit_portfolio_id' => $request->wholesale_deposit_portfolio_id
            ]);
            $orgAfter = Organization::where("id",$request->org)->first()->toArray();
            // Update organization demographic data
            OrganizationDemoGraphicData::where("organization_id", $request->org)->update([              
                'value_of_assets' => $request->total_asset_size
            ]);
                 
            
            // Retrieve data after update
            // $orgAfter = $org->fresh()->toArray();
            $creditRatingAfter = DepositCreditRating::where("organization_id", $request->org)->first()->toArray();
            $orgDemoAfter = OrganizationDemoGraphicData::where('value_of_assets',$orgDemoBefore['value_of_assets'])
                ->where('organization_id',$request->org)
            ->first();    
            // Compare data before and after update
            $orgGeneralInfomatch = ($orgBefore == $orgAfter);
            $orgCreditRatingMatch=true;
            if($creditRatingBefore){
                if($creditRatingBefore['credit_rating_type_id'] != $creditRatingAfter['credit_rating_type_id']){
                    $orgCreditRatingMatch=false;
                }else{
                    if($creditRatingBefore['deposit_insurance_id'] != $creditRatingAfter['deposit_insurance_id']){
                        $orgCreditRatingMatch=false;
                    }
                }
            }



            $orgDemoDatamatch = ($orgDemoAfter===null)?false:true;          
            $recordschanged=false;
            if(($orgDemoDatamatch && $orgGeneralInfomatch && $orgCreditRatingMatch)==true){
                $recordschanged=false;
            } else if(($orgDemoDatamatch && $orgGeneralInfomatch && $orgCreditRatingMatch)==false) {
                $recordschanged=true;
            }
    
            DB::commit();  
            $thisorg = Organization::with(['WholeSaleDepositsPortfolio', 'NAICSCode', 'PotentialYearlyDeposit', 'demographicData', 'depositCreditRating'])->find($request->org);
            return response()->json([
                'success' => true,
                'message' => 'Additional Information Updated Successfully',
                'recordsChanged' => $recordschanged,
                'orgGeneralInfomatch'=> $orgGeneralInfomatch,
                'orgCreditRating'=> $orgCreditRatingMatch,
                'orgDemoDatamatch'=> $orgDemoDatamatch,
                '$orgDemoBefore'=>$orgDemoBefore,
                'orgDemoAfter'=>$orgDemoAfter,
                'value_of_assets' => $orgDemoBefore['value_of_assets'],
                'thisorg' => $thisorg
            ]);

        } catch (Exception $exp) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => 'Updated Failed.' . $exp->getMessage()]);
        }
    }
    
    public function updateOrganizationBioInfo(Request $request)
    {
        try {
            DB::beginTransaction();
            $orgdemob = OrganizationDemoGraphicData::where("organization_id", $request->org)->first()->toArray();
            $savedemoorg['org_bio'] = $request->bio;
            $orgdemo = OrganizationDemoGraphicData::where("organization_id", $request->org)->update($savedemoorg);
            $thisorg = Organization::with(['WholeSaleDepositsPortfolio', 'NAICSCode', 'PotentialYearlyDeposit', 'demographicData', 'depositCreditRating'])->find($request->org);
          
            DB::commit();

            return response()->json(['success' => true,
             'message' => 'Bio Information Updated Successfully', 
             'thisorg' => $thisorg,
             '$orgdemob'=> $orgdemob,
             'somerecordschanged'=>($orgdemob['org_bio']==$request->bio)? false: true
            ]);

        } catch (Exception $exp) {
            return response()->json(['success' => false, 'message' => 'Updated Failed.' . $exp->getMessage()]);
        }

    }
    public function deleteFIFile(Request $request){
        try {
            DB::beginTransaction();
            $thisorg = Organization::with(['WholeSaleDepositsPortfolio', 'NAICSCode', 'PotentialYearlyDeposit', 'demographicData', 'depositCreditRating'])->find($request->org);
            if($request->type=="transferfile"){


                $doctype= DB::table("document_types")->where("type_name","Transfer Details")->first();
                $founddoc= OrganizationDocument::where("organization_id",$thisorg->id)->where("type_id",$doctype->id)->first();
                if($founddoc){
                  $orgtoupdate= OrganizationDocument::findOrFail($founddoc->id);
                  $orgtoupdate->delete();
                }else{
                    return response()->json(['success' => true, 'message' => 'Document not found', 'thisorg' => $thisorg],200);     
                }

            }else if($request->type=="financialfile"){

                $doctype= DB::table("document_types")->where("type_name","Financial Statement")->first();
                $founddoc= OrganizationDocument::where("organization_id",$thisorg->id)->where("type_id",$doctype->id)->first();
                if($founddoc){
                  $orgtoupdate= OrganizationDocument::findOrFail($founddoc->id);
                  $orgtoupdate->delete();
                }else{
                    return response()->json(['success' => true, 'message' => 'Document not found', 'thisorg' => $thisorg],200);     
                }

            }
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Document Deleted Successfully', 'thisorg' => $thisorg],200);
        } catch (Exception $exp) {
            return response()->json(['success' => false, 'message' => 'Deletion Failed.' . $exp->getMessage()],400);
        }
    }
    public function updateOrganizationTransferDetails(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $thisorg = Organization::with(['WholeSaleDepositsPortfolio', 'NAICSCode', 'PotentialYearlyDeposit', 'demographicData', 'depositCreditRating'])->find($request->org);
            $logo = "";
            $saveorggeninfo = [];
            $in=[];
            if ($request->hasFile("transfer_doc")) {
                $allowedTypes = ['pdf'];
                $maxSize = 5000000;
                $uploadedFile = $request->file('transfer_doc');
                $folder = "documents/bank";
                $name = str_replace(" ", "_", $thisorg->name."_transfer_details".$thisorg->id);

                $filePath = $this->uploadDocumentFile($uploadedFile, $folder, $allowedTypes, $maxSize, $request->institution_name,$name);
                
                
                if ($filePath['status'] === 1) {
                    $fileee = $folder."/".$filePath['path'];
                    //get id of type
                    $doctype= DB::table("document_types")->where("type_name","Transfer Details")->first();
                    //get Id of type
                    $in=[
                        'organization_id' => $thisorg->id,
                        'type_id' =>  $doctype->id,
                        'file_name' =>  $fileee,
                        'user_uploaded_file_name' =>  $uploadedFile->getClientOriginalName(),
                        'created_at' => getUTCTimeNow(), 
                        'updated_at' => getUTCTimeNow(), 
                    ];
                   $founddoc= OrganizationDocument::where("organization_id",$thisorg->id)->where("type_id",$doctype->id)->first();

                   if($founddoc){

                   $orgtoupdate= OrganizationDocument::findOrFail($founddoc->id);


                   $orgtoupdate->update($in);

                   }else{

                       OrganizationDocument::create($in);
                       
                   }   
                   
                }else{
                    return response()->json(['success' => false, 'message' => 'Updated Failed.','data'=>$filePath]);
                }

            }
            if ($request->hasFile("financial_statement_file")) {
                $allowedTypes = ['pdf'];
                $maxSize = 5000000;
                $uploadedFile = $request->file('financial_statement_file');
                $folder = "documents/bank";
                $name = str_replace(" ", "_", $thisorg->name."_financial_statement".$thisorg->id);

                $filePath = $this->uploadDocumentFile($uploadedFile, $folder, $allowedTypes, $maxSize, $request->institution_name,$name);
                
                
                if ($filePath['status'] === 1) {
                    $fileee = $folder."/".$filePath['path'];
                    //get id of type
                    $doctype= DB::table("document_types")->where("type_name","Financial Statement")->first();
                    //get Id of type
                    //insert
                    $in=[
                        'organization_id' => $thisorg->id,
                        'type_id' =>  $doctype->id,
                        'file_name' =>  $fileee,
                        'user_uploaded_file_name'=>$uploadedFile->getClientOriginalName(),
                        'created_at' => getUTCTimeNow(), 
                        'updated_at' => getUTCTimeNow(), 
                    ];
                   $founddoc= OrganizationDocument::where("organization_id",$thisorg->id)->where("type_id",$doctype->id)->first();
                   if($founddoc){
                   $orgtoupdate= OrganizationDocument::findOrFail($founddoc->id);
                   $orgtoupdate->update($in);
                   }else{
                       OrganizationDocument::create($in);
                   }               
                    
                
                    //insert
                   
                }else{

                    return response()->json(['success' => false, 'message' => 'Updated Failed.','data'=>$filePath]);

                }

            }
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Information Updated Successfully', 'thisorg' => $thisorg,'filedetails'=>$in]);
        } catch (Exception $exp) {
            return response()->json(['success' => false, 'message' => 'Updated Failed.' . $exp->getMessage()]);
        }

    }

    
}
