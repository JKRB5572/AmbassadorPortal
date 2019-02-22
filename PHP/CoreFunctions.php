<?php

//Function to set custom page title
function pageTitle($title = "Ambassador Portal"){
    echo $title;
}


//Function to check database and see if a page is active otherwise redirect to index
function checkPageIsActive($pageName, $return = False){
	$pageStatus = sqlFetch("SELECT status, accessLevel FROM PageStatus WHERE pageName = '".$pageName."'", "ASSOC");
	if($return == False){
		if( isset($_SESSION["accessLevel"]) && $_SESSION["accessLevel"] != 5 && ($pageStatus[0]["status"] == 0 || $pageStatus[0]["accessLevel"] > $_SESSION["accessLevel"]) ){
			header("location: index.php");
		}
	}
	else{
		if( isset($_SESSION["accessLevel"]) && $_SESSION["accessLevel"] != 5 && ($pageStatus[0]["status"] == 0 || $pageStatus[0]["accessLevel"] > $_SESSION["accessLevel"]) ){
			return False;
		}
		else{
			return True;
		}
	}
}


//Function to an html link with active if the page is active
function returnAnchor($href, $prefix){
	$directory = explode("/", $_SERVER['PHP_SELF']);
	$checkPrefix = $directory[1];

	if(strpos($_SERVER['PHP_SELF'], $href) !== false && $checkPrefix == $prefix){
		echo '<a href="/'.$prefix."/".$href.'" class="active">';
	}
	else{
		echo '<a href="/'.$prefix."/".$href.'">';
	}
}


//Function to return the user's given name if set or forename otherwise 
function returnName(){
	if($_SESSION["givenName"] !== ""){
		return $_SESSION["givenName"];
	}
	else{
		return $_SESSION["forename"];
	}
}


//Function to return full name of an ambassador/admin
function returnFullName($surname, $forename, $givenName, $decrypt = false){
	if($decrypt == true){
		$surname = decrypt($surname);
		$forename = decrypt($forename);
		$givenName = decrypt($givenName);
	}
	$returnString = $forename." ";
	if($givenName){
		$returnString .= "(".$givenName.") ";
	}
	$returnString .= $surname;
	return $returnString;
}


function fetchName($userID, $table){
	if(isset($userID)){
		if(strtolower($table) == "ambassadors"){
			$details = sqlFetch("SELECT surname, forename, givenName FROM Ambassadors WHERE universityID = '".$userID."'", "ASSOC");
		}
		elseif(strtolower($table) == "admin"){
			$details = sqlFetch("SELECT surname, forename, givenName FROM Admin WHERE adminID = '".$userID."'", "ASSOC");
		}
		else{
			return "Error - '".$table."' is not a supported argument";
		}
		$details = $details[0];
		$returnValue = returnFullName($details["surname"], $details["forename"], $details["givenName"], True);
		return $returnValue;
	}
}


function fetchEventTopics($array){
	if($array != ""){

		if(gettype($array) != "array"){
			$array = json_decode($array);
		}

		$topics = array();
		foreach($array as $topicID){
			$topicName = sqlFetch("SELECT topicName FROM Topics WHERE topicID = ".$topicID, "NUM");
			array_push($topics, $topicName[0][0]);
		}

		return $topics;

	}
	else{
		return NULL;
	}

}


function echoEventTopics($array, $return = False){
	if($array != ""){

		if(gettype($array) != "array"){
			$array = json_decode($array);
		}

		$returnString = $array[0];
		$i = 1;
		for($i; $i < count($array) - 1; $i++){
			$returnString.= ", ".$array[$i];
		}
		if(isset($array[$i])){
			$returnString .= " and ".$array[$i];
		}


		if($return = False){
			echo $returnString;
		}
		else{
			return $returnString;
		}
	
	}
	else{
		return NULL;
	}

}


function verboseDateHTML($numericDateString){
	$date = explode("-", $numericDateString);
	$year = $date[0];
	$month = $date[1];
	$day = $date[2];
	$suffix = "th";

	if($day[1] == "1" && $day != "11"){
		$suffix = "st";
	}
	else if($day[1] == "2" && $day != "12"){
		$suffix = "nd";
	}
	else if($day[1] == "3" && $day != "13"){
		$suffix = "rd";
	}

	if($day[0] == "0"){
		$day = $day[1];
	}

	return $day."<sup>".$suffix."</sup> ".MonthName($month)." ".$year;
}


function monthName($numericMonth){
	$months = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
	return $months[(int)$numericMonth - 1];
}


function boolToPolar($value){
	if($value == 0){
		return "No";
	}
	else{
		return "Yes";
	}
}


function echoTableRow($arrayVariable, $textOutput, $decrypt = False){
    $tableRow = "<tr>";

    if($arrayVariable != null){
        $tableRow .= "<th>".$textOutput."</th>";

        if($decrypt == False){
            $tableRow .= "<td>".$arrayVariable."</td>";
        }
        else{
            $tableRow .= "<td>".decrypt($arrayVariable)."</td>";
        }
    }
    else{
        $tableRow .= "<th style='color: grey;'>".$textOutput."</th><td>---</td>";
    }

    $tableRow .= "</tr>";
    echo $tableRow;
}


function echoTableRowRequired($arrayVariable, $textOutput, $decrypt = False){
    $tableRow = "<tr>";

    if($arrayVariable != null){
        $tableRow .= "<th>".$textOutput."</th>";

        if($decrypt == False){
            $tableRow .= "<td>".$arrayVariable."</td>";
        }
        else{
            $tableRow .= "<td>".decrypt($arrayVariable)."</td>";
        }
    }
    else{
        $tableRow .= "<th style='color: red;'>".$textOutput."</th><td>---</td>";
    }

    $tableRow .= "</tr>";
    echo $tableRow;
}


function createNotificationAdmin($text, $accessLevel, $category = null, $target = null){

	$query = "INSERT INTO NotifcationsAdmin(text, audienceAccessLevel ";
	
	if(isset($category)){
		$query .= ", category";
	}
	if(isset($target)){
		$query .= ", target";
	}

	$query .= ") VALUES ('".encrypt($text)."', '".$accessLevel."'";

	if(isset($category)){
		$query .= ", '".$category."'";
	}
	if(isset($target)){
		$query .= ", '".$target."'";
	}
	
	if(sqlInsert($query, True, True) == False){
		echo "<script>console.log('ERROR: Failed to create notification');</script>";
	}
}


function createNotificationAmbassador($text, $category = null, $target = null){

	$query = "INSERT INTO NotificationsAmbassador(text";
	
	if(isset($category)){
		$query .= ", category";
	}
	if(isset($target)){
		$query .= ", targetIndividual";
	}

	$query .= ") VALUES ('".encrypt($text)."'";

	if(isset($category)){
		$query .= ", '".$category."'";
	}
	if(isset($target)){
		$query .= ", '".$target."'";
	}

	$query .= ")";

	if(sqlInsert($query, True, True) == False){
		echo "<script>console.log('ERROR: Failed to create notification');</script>";
	}
}


?>