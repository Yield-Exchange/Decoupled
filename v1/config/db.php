<?php
    require_once "Core.php";

    if ( !in_array($_SERVER['REMOTE_ADDR'], ['127.0.0.1', '::1']) ) {
        define("BASE_DIR", $_SERVER['DOCUMENT_ROOT']);
    }else{
        define("BASE_DIR", isset($DOCUMENT_ROOT) ? $DOCUMENT_ROOT : $_SERVER['DOCUMENT_ROOT']);
    }

    if ( session_status() == PHP_SESSION_NONE ) {
        session_start();
    }

    class db{

        public static function open(){
            global $DB_SERVER,$DB_USER,$DB_PASS,$DB_NAME;

            $db = mysqli_connect($DB_SERVER,$DB_USER,$DB_PASS,$DB_NAME);
            if ($db->connect_errno > 0){
              echo "Failed to connect to MySQL: " . $db->connect_error;
            }
              
            return $db;
        }
    
        public static function close(&$db){
            $db->close();
        }

        public static function exists($query){
            if (self::getRecords($query) > 0){
                return true;
            }
            return false;
        }
        
        //Get Multiple records
        public static function getRecords($query, $cursor=NULL, $pageSize=NULL, $boolean_if_empty=true){
        // Gets multiple records and returns associative array
            $db = db::open();
            if (!is_null($cursor) && !is_null($pageSize)){
                $query .= " LIMIT ".$cursor.", ".$pageSize;
                }
            $result = $db->query($query);
            if(!$result){
                die('There was an error running the query [' . $db->error . ']');
            }
            if($result->num_rows>0){
                $i=0;
    
                while ($row = $result->fetch_assoc()){
                    $recordset[$i] = $row;  //placing entire row at single index 
                    $i++;
                    }
                }
            else
            {
                $recordset = $boolean_if_empty ? false : [];
            }
            db::close($db);
            return ($recordset);
        }//End Function
    
       // Gets single record and returns single associative array
        public static function getRecord($query){ 
            $db = db::open();
            $result = $db->query($query);
            if($result->num_rows>0){
                $recordset = $result->fetch_assoc();
            }else{
                $recordset = false;
            }
            db::close($db);
            return ($recordset);
        }//End Function
    
        public static function getCell($query){ // Returns single value
            $db = db::open();
            $result = $db->query($query);
            if($result->num_rows>0){
                $cell = $result->fetch_array();
                return $cell[0];
            }else{
                $cell = false;
            }
            return $cell;
        }

        public static function insertRecord($query){ // Gets a formatted query to insert a row and returns the ID of last added record
            $db = db::open();
            $db->query($query);
            $result = $db->insert_id;
            db::close($db);
            return ($result);
        }
    
        public static function query($query){  // Executes any query
            $db = db::open();
            $result = $db->query($query);
            db::close($db);
            return ($result);
        }
        
        public static function preparedQuery($query, $params=array())
        {
            $connection = db::open();
        
            // Create a prepared statement
            $stmt = $connection->prepare($query);
            if (!$stmt)
               die("Query is not valid : ".$connection->error);
            
            // Bind parameters
            if (count($params) > 1)
                @call_user_func_array(array($stmt, 'bind_param'), db::RefValues($params));
            
            // Execute and buffer results into memory
            $stmt->execute();
//            echo  $stmt->error;
            
            // Return results based on query type
            $type = db::GetQueryType($query);

            if ($stmt->errno !== 0){
                $result = FALSE;
            } else if ($type == "SELECT"){
                $meta = $stmt->result_metadata();
        
                // Fetch field names
                while ($field = $meta->fetch_field())
                    $parameters[] = &$row[$field->name];
        
                call_user_func_array(array($stmt, 'bind_result'), db::RefValues($parameters));
        
                $results = array();
                while ($stmt->fetch())
                {
                    $tmp = array();
                    foreach ($row as $key => $val)
                        $tmp[$key] = $val;
                    $results[] = $tmp;
                }
                $stmt->close();
                return $results;
            } else if(in_array($type, array("INSERT"))){
                $stmt->close();
                return $connection->insert_id;
            } else if (in_array($type, array("UPDATE", "DELETE"))){
                $result = $stmt->affected_rows;
            }else // CREATE TABLE, REPLACE
            {
                $result = TRUE;
            }
            
            // Close the prepared statement with MySQL
            $stmt->close();
            return $result;
        }
        
        public static function GetQueryType($query)
        {
            $query = strtoupper($query);
            $best_type = NULL;
            foreach(array("SELECT", "INSERT", "UPDATE", "DELETE", "REPLACE", "CREATE TABLE") as $type) {
                if(strpos($query, $type) !== false){
                    $best_type = $type;
                    break;
                }
            }
            
            return $best_type;
        }
        
        public static function RefValues($arr)
        {
            if (strnatcmp(phpversion(), '5.3') >= 0) //Reference is required for PHP 5.3+
            {
                $refs = array();
                foreach($arr as $key => $value)
                    $refs[$key] = &$arr[$key];
                return $refs;
            }
            return $arr;
        }

    }