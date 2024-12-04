<style>
    p{
        color:grey;
        font-size: 13px;
    }
</style>
<?php
    $current_file = basename($_SERVER['REQUEST_URI'], '?' . $_SERVER['QUERY_STRING']);
    if ($current_file == "c_details"){

    }else {

        require_once BASE_DIR . '/depositor/inc/offer_summary.php';
        if (empty($transparent_request_summary)) {
            echo "<div class='card p-3'>";
        }

        //    if (in_array($current_file,['comp_details','c_details','contract_offer_view'])){
        $contract = BankModel::getBankBidContract($offer['id']);
        if (!empty($contract)) {
            require_once BASE_DIR . '/depositor/inc/contract_summary.php';
        } else {
            require_once BASE_DIR . '/depositor/inc/request_summary.php';
        }

        if (empty($transparent_request_summary)) {
            echo "</div>";
        }
    }
?>