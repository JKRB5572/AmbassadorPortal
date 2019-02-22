<?php

//Function to perform an SQL Query and return result as array
function sqlQuery($query){
    $con = mysqli_connect($_ENV["DATABASE_SERVER"], $_ENV["DATABASE_USERNAME"], $_ENV["DATABASE_PASSWORD"], $_ENV["DATABASE_NAME"]);
    if (!$con) {
        echo "Error: Unable to connect to MySQL." . PHP_EOL;
        echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }

    $result = mysqli_query($con, $query);
    
    if($result === FALSE){
        return "QUERY ERROR: ".$query;
    }
    else{
        return $result;
    }
}


function sqlFetch($query, $type){

    $testQuery = sqlQuery($query);
    if(is_string($testQuery)){
        if(substr(sqlQuery($query), 0, 11) == "QUERY ERROR"){
            echo $testQuery;
        }
    }
    else{

        if($type == "NUM"){
            $returnArray = mysqli_fetch_all(sqlQuery($query), MYSQLI_NUM);
        }
        else if($type == "ASSOC"){
            $returnArray = mysqli_fetch_all(sqlQuery($query), MYSQLI_ASSOC);
        }
        else if($type == "BOTH"){
            $returnArray = mysqli_fetch_all(sqlQuery($query), MYSQLI_BOTH);
        }
        else{
            return "Unregonised type ".$type;
        }
        return $returnArray;

    }

}


//Function to insert data to SQL database
function sqlInsert($query, $debug = False, $returnDebug = False){
    $con = mysqli_connect($_ENV["DATABASE_SERVER"], $_ENV["DATABASE_USERNAME"], $_ENV["DATABASE_PASSWORD"], $_ENV["DATABASE_NAME"]);

    if (!$con) {
	    echo "Error: Unable to connect to MySQL." . PHP_EOL;
	    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
	    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
        exit;
    }
    
    else if($debug == True){
        if(mysqli_query($con, $query)){
            if($returnDebug == True){
                return True;
            }
            else{
                echo "<br/><strong>Insertion Successful</strong><br/>{$query}";
            }
        }
        else{
            if($returnDebug == True){
                return False;
            }
            else{
                echo "<br/><strong>Insertion Failed</strong><br/>{$query}";
            }
        }
    }

    else{
        mysqli_query($con, $query);
    }
    
}


//Function to update data
function sqlUpdate($query, $debug = False, $returnDebug = False){
    if(strpos($query, 'WHERE') !== false){
        $con = mysqli_connect($_ENV["DATABASE_SERVER"], $_ENV["DATABASE_USERNAME"], $_ENV["DATABASE_PASSWORD"], $_ENV["DATABASE_NAME"]);

        if (!$con) {
            echo "Error: Unable to connect to MySQL." . PHP_EOL;
            echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
            exit;
        }
        
        else if($debug == True){
            if(mysqli_query($con, $query)){
                if($returnDebug == True){
                    return True;
                }
                else{
                    echo "<br/><strong>Update Successful</strong><br/>{$query}";
                }
            }
            else{
                if($returnDebug == True){
                    return False;
                }
                else{
                    echo "<br/><strong>Update Failed</strong><br/>{$query}";
                }
            }
        }

        else{
            mysqli_query($con, $query);
        }
    }
    else{
        echo "Error: WHERE cause omitted so query killed";
    }
}


function sqlDelete($query){
    if(strpos($query, 'WHERE') !== false){
        $con = mysqli_connect($_ENV["DATABASE_SERVER"], $_ENV["DATABASE_USERNAME"], $_ENV["DATABASE_PASSWORD"], $_ENV["DATABASE_NAME"]);

        if (!$con) {
            echo "Error: Unable to connect to MySQL." . PHP_EOL;
            echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
            echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
            exit;
        }
        else{
            mysqli_query($con, $query);
        }
    }
    else{
        echo "Error: WHERE cause omitted so query killed";
    }
}

?>