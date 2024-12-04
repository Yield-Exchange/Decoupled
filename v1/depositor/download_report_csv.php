<?php
session_start();
require_once "../config/db.php";
require_once "../config/Model.php";

if (empty($_POST)) {exit;}

$user = $_SESSION['email'];

$query = "SELECT id from depositer where email='$user'";
$dep_id = db::getCell($query);

$get_cont = "select reff as RefferenceId ,bank_name as BankName,dep_amount as DepositAmount ,comp_date as CompletionDate from contracts_data where dep_id='$dep_id' AND comp_date BETWEEN '" . $_POST['date_from'] . "' AND '" . $_POST['date_to'] . "'";
$rzlt = db::getrecords($get_cont);

if (!empty($_REQUEST['download_csv'])) {

    // Original PHP code by Chirp Internet: www.chirp.com.au
    // Please acknowledge use of this code by including this header.

    function cleanData(&$str)
    {
        $str = preg_replace("/\t/", "\\t", $str);
        $str = preg_replace("/\r?\n/", "\\n", $str);
        if (strstr($str, '"')) {
            $str = '"' . str_replace('"', '""', $str) . '"';
        }

    }

    // filename for download
    $filename = "reports" . date('Ymd') . ".xls";

    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Content-Type: application/vnd.ms-excel");

    $flag = false;
    // $result = pg_query("SELECT * FROM table ORDER BY field") or die('Query failed!');
    foreach ($rzlt as $row) {

        if (!$flag) {
            // display field/column names as first row
            echo implode("\t", array_keys($row)) . "\r\n";
            $flag = true;
        }
        array_walk($row, __NAMESPACE__ . '\cleanData');
        echo implode("\t", array_values($row)) . "\r\n";

    }
    exit;

}
