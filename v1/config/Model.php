<?php

class Model{

    public static function generateUniqueOfferID( $start=100001 ){

        $offerID = db::getCell("SELECT reference_no FROM offers ORDER BY reference_no DESC LIMIT 1");
        if(empty($offerID)){

            $offers = db::getCell("SELECT COUNT(*) FROM offers WHERE reference_no='$start'");
            if ($offers > 0){
                return self::generateUniqueOfferID($start+1);
            }

            return $start;
        }

        $offerID++;
        $offers = db::getCell("SELECT COUNT(*) FROM offers WHERE reference_no='$offerID' LIMIT 1");

        if ($offers > 0){
            return self::generateUniqueOfferID($offerID+1);
        }

        return $offerID;
    }

    public static function HighlightUrl($current_file,$matches){
        return in_array($current_file,$matches);
    }

    public static function todayDate() {
        return date('Y-m-d');
    }

    public static function dateTime() {
        return date('Y-m-d H:i:s');
    }

    public static function dateTimeToUTC($format,$date,$timezone=null) {
        if (empty($timezone)) {
            $date = new DateTime($date);
        }else{
            $date = new DateTime($date,new DateTimeZone(self::formattedTimezone($timezone)));
        }
        $date->setTimezone(new DateTimeZone('UTC'));
        return $date->format($format);
    }

    public static function dateTimeFromUTC($format,$date,$timezone=null) {
        $date = new DateTime($date,new DateTimeZone("UTC"));
        if (!empty($timezone)) {
            $date->setTimezone(new DateTimeZone(self::formattedTimezone($timezone)));
        }else {
            $date->setTimezone(new DateTimeZone(date_default_timezone_get()));
        }
        return $date->format($format);
    }

    public function get_timezone_abbreviation($date,$timezone){
        $date = new DateTime($date,new DateTimeZone("UTC"));
        if (!empty($timezone)) {
            $date->setTimezone(new DateTimeZone(self::formattedTimezone($timezone)));
        }else {
            $date->setTimezone(new DateTimeZone(date_default_timezone_get()));
        }
        return $dateTime->format('T');

    }

    public static function utcDateTime() {
        return gmdate('Y-m-d H:i:s');
    }

    public static function utcDate() {
        return gmdate('Y-m-d');
    }

    public static function formattedTimezone($timezone){
        $timezone=strtolower(trim($timezone));
        if (strcmp($timezone, "newfoundland") == 0) {
            $zone = "America/St_Johns";
        } else if ( strcmp($timezone, "atlantic") == 0) {
            $zone = "America/Halifax";
        } else if (strcmp($timezone, "atlantic no dst") == 0) {
            $zone = " America/Blanc-Sablon";
        } else if (strcmp($timezone, "eastern") == 0) {
            $zone = "America/Toronto";
        } else if (strcmp($timezone, "eastern no dst") == 0) {
            $zone = "America/Atikokan";
        } else if (strcmp($timezone, "central") == 0) {
            $zone = "America/Winnipeg";
        } else if (strcmp($timezone, "central no dst") == 0) {
            $zone = "America/Regina";
        } else if (strcmp($timezone, "mountain") == 0) {
            $zone = "America/Edmonton";
        } else if (strcmp($timezone, "mountain no dst") == 0) {
            $zone = "America/Creston";
        } else if (strcmp($timezone, "pacific") == 0) {
            $zone = "America/Vancouver";
        } else {
            $zone = "America/Winnipeg";
        }

        return $zone;
    }

    public static function timezonesList(){
        return [
            'Newfoundland'=>'Newfoundland - St_Johns, GMT '.((new DateTime('now', new DateTimeZone('America/St_Johns')))->format('P')),
            'Atlantic'=>'Atlantic - Halifax, GMT '.((new DateTime('now', new DateTimeZone('America/Halifax')))->format('P')),
            'Atlantic no DST'=>'Atlantic no DST - America/Atikokan, GMT '.((new DateTime('now', new DateTimeZone('America/Blanc-Sablon')))->format('P')),
            'Eastern'=>'Eastern - America/Toronto, GMT '.((new DateTime('now', new DateTimeZone('America/Toronto')))->format('P')),
            'Eastern no DST'=>'Eastern no DST - America/Atikokan, GMT '.((new DateTime('now', new DateTimeZone('America/Atikokan')))->format('P')),
            'Central'=>'Central - America/Winnipeg, GMT '.((new DateTime('now', new DateTimeZone('America/Winnipeg')))->format('P')),
            'Central no DST'=>'Central no DST - America/Regina, GMT '.((new DateTime('now', new DateTimeZone('America/Regina')))->format('P')),
            'Mountain'=>'Mountain - America/Edmonton, GMT '.((new DateTime('now', new DateTimeZone('America/Edmonton')))->format('P')),
            'Mountain no DST'=>'Mountain no DST - America/Creston, GMT '.((new DateTime('now', new DateTimeZone('America/Creston')))->format('P')),
            'Pacific'=>'Pacific - America/Vancouver, GMT '.((new DateTime('now', new DateTimeZone('America/Vancouver')))->format('P'))
        ];
    }

    public static function nice_number($n)
    {
        // first strip any formatting;
        $n = @(0 + str_replace(",", "", $n));

        // is this a number?
        if (!is_numeric($n)) {
            return false;
        }

        // now filter it;
        if ($n >= 1000000000000) {
            return round(($n / 1000000000000), 2) . ' T';
        } elseif ($n >= 1000000000) {
            return round(($n / 1000000000), 2) . ' B';
        } elseif ($n >= 1000000) {
            return round(($n / 1000000), 2) . ' M';
        } elseif ($n >= 1000) {
            return round(($n / 1000), 2) . ' K';
        }

        return number_format($n);
    }

    public static function countArray($data){
        return !empty($data) ? count($data) : 0;
    }

    public static function getInterest($get_val, $is_variable=false){
        if ($get_val != null && $get_val > 0) {
            if ($get_val / 2 < 0.5) {
                $get_val = floatval($get_val);
                $a = round($get_val, 2);
                if (strpos($a, ".") !== false) {
                    return number_format($a, 2) . "%";
                } else {
                    return $a . ".00%";
                }
            } else {
                $a = round($get_val, 2);
                if (strpos($a, ".") !== false) {
                    return number_format($a, 2) . "%";
                } else {
                    return $a . ".00%";
                }
            }
        }else{
            return '-';
        }

    }
}

include __DIR__."/../config/BankModel.php";
include __DIR__."/../config/DepositorModel.php";