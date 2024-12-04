<?php
session_start();
include "../../config/db.php";
include "../../config/Model.php";
require_once BASE_DIR."/config/AuthModel.php";
require_once BASE_DIR."/config/RequestsModel.php";

if (AuthModel::isLoggedIn()){
    if( !AuthModel::authAjaxCsrfToken() ){
        return;
    }

    $action = $_POST['ACTION'];
    switch ($action){
        case 'FETCH_REVIEW_OFFERS':
            $req_id = $_POST['req_id'];
            $offers = BankModel::getOffersByRequestID($req_id,true);
            $response=array();

            if (!empty($offers)) {
                $data = RequestsModel::getRequestByID($req_id);
                foreach ($offers as $offer) {
                    $contract = BankModel::getTheContractByOfferID($offer['id']);

                    $bank = AuthModel::getUserDataByID($offer['invited_user_id']);
                    $institution_column = $bank["name"].'<input type="hidden" value="'.$offer['id'].'" class="form-control" name="id"/>';
                    $interest_column = BankModel::getInterest($offer["interest_rate_offer"]).'<input type="hidden" class="form-control" name="rate" value="'.Core::render($offer["interest_rate_offer"]).'"/>';
                    $minimum_amount = $data['currency'].' '.number_format($offer["minimum_amount"]).'<input type="hidden" class="form-control" name="min_amount" value="'.Core::render($offer['minimum_amount']).'"/>';
                    $maximum_amount = $data['currency'].' '.number_format($offer["maximum_amount"]).'<input type="hidden" class="form-control" name="max_amount" value="'.Core::render($offer['maximum_amount']).'"/>';
                    $action_column = '<a href="award?id='.Core::urlValueEncrypt($offer["id"]).'&&rqid='.Core::urlValueEncrypt($_POST['req_id']).'" class="btn btn-primary" style="color:white">View</a>';

                    $offered_amount_column = '<div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span style="padding-right: 5px;padding-top: 20px;">
                                                        '.Core::render($data['currency']).'
                                                    </span>
                                                </div>
                                                <input data-rec-id="'.$offer['id'].'" type="text" onkeyup="addThousands(this);" class="form-control offered_amount" name="offered_amount" value="" disabled />
                                            </div>
                                            <div class="_error"></div>';

                    $utc_date_now = Model::utcDate();
                    $utc_time_now = Model::utcDateTime();
                    if ($data['closing_date_time'] > $utc_time_now){
                        $action_column.=' <button class="btn btn-no-action-custom" disabled>Select</button>';
                    }else{
                        $action_column.=' <button class="btn btn-outline-secondary-custom" data-selected="0" onclick="selectOffer(this);">Select</button>';
                    }

                    if ($utc_date_now > $data['date_of_deposit']){
                        $action_column = '<a href="award?id='.Core::urlValueEncrypt($offer["id"]).'&&rqid='.Core::urlValueEncrypt($_POST['req_id']).'" class="btn btn-primary" style="color:white">View</a>';
                        $action_column .=' <button class="btn btn-no-action-custom" disabled>Select</button>';
                        $offered_amount_column = '<div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span style="padding-right: 5px;padding-top: 20px;">
                                                        '.Core::render($data['currency']).'
                                                    </span>
                                                </div>
                                                <input type="text" onchange="addThousands(this);" class="form-control offered_amount" disabled name="offered_amount" value="" />
                                            </div>
                                            <div class="_error"></div>';
                    }

                    $response[] = array($institution_column, $interest_column, $minimum_amount, $maximum_amount, $action_column, $offered_amount_column);
                }
            }

            $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => !empty($offers) ? count($offers) : 0,
                "recordsFiltered" => !empty($offers) ? count($offers) : 0,
                "data" => $response,
            );

            // Output to JSON format
            echo json_encode($output);
            break;
        case 'SHOW_CONFIRM_BUTTON':
            $req_id = $_POST['req_id'];
            $data = RequestsModel::getRequestByID($req_id,true);
            $utc_date_now = Model::utcDate();
            $utc_time_now = Model::utcDateTime();

            $show=1;
            if ($data['closing_date_time'] > $utc_time_now){
                $show=0;
            }

            if ($utc_date_now > $data['date_of_deposit']){
                $show=0;
            }

            echo json_encode($show);
            break;
    }
}