<?php

require_once "/var/www/html/PHP/Config.php";
require_once "/var/www/html/PHP/CoreFunctions.php";
require_once "/var/www/html/PHP/Encryption.php";
require_once "/var/www/html/PHP/FormFunctions.php";
require_once "/var/www/html/PHP/SQLFunctions.php";


if($_SERVER["REQUEST_METHOD"] === "POST"){

    if(!empty($_POST["month"]) && !empty($_POST["year"])){
        $month = validateInput($_POST["month"]);
        $year = validateInput($_POST["year"]);

        if(isset($_POST["limit"])){
            $limit = validateInput($_POST["limit"]);
        }

        $query = "SELECT eventID, eventName, project, fundingSource, eventDate, startTime, endTime, type, visibility FROM EventPrimary WHERE eventDate LIKE '{$year}-{$month}%' ";
        
        if(isset($limit) && $limit == "true"){
            $query .= "AND eventDate >= CURDATE() ";
        }

        $query .= "ORDER BY eventDate, startTime";

        $monthsEvents = sqlFetch($query, "ASSOC");

        $returnArray = array();

        foreach($monthsEvents as $event){
            if($event["visibility"] == 0 || ($event["visibility"] == 1 && $_SESSION["accessLevel"] >= 1) || ($event["visibility"] == 2 && $_SESSION["accessLevel"] >= 2)){
                $addressInformation = sqlFetch("SELECT address1, postcode FROM EventLocation WHERE eventID = '".$event["eventID"]."'", "ASSOC");
                $addressInformation = $addressInformation[0];

                $trainingRequired = sqlFetch("SELECT trainingRequired FROM EventAmbassadors WHERE eventID = '".$event["eventID"]."'", "NUM");

                $topic = sqlFetch("SELECT topic FROM EventWorkshop WHERE eventID = '".$event["eventID"]."'", "NUM");

                $event["address1"] = $addressInformation["address1"];
                $event["postcode"] = $addressInformation["postcode"];
                $event["trainingRequired"] = $trainingRequired[0][0];
                $event["topic"] = $topic[0][0];

                $returnArray[] = array(
                    "eventID" => $event["eventID"],
                    "eventName" => decrypt($event["eventName"]),
                    "project" => decrypt($event["project"]),
                    "fundingSource" => decrypt($event["fundingSource"]),
                    "eventDate" => $event["eventDate"],
                    "startTime" => $event["startTime"],
                    "endTime" => $event["endTime"],
                    "type" => decrypt($event["type"]),
                    "address1" => decrypt($addressInformation["address1"]),
                    "postcode" => decrypt($addressInformation["postcode"]),
                    "trainingRequired" => $trainingRequired[0][0],
                    "topicID" => $topic[0][0],
                    "topicString" => verboseList(fetchTopics($topic[0][0])),
                    "visibility" => $event["visibility"]
                );
            }
        }



        echo json_encode($returnArray);

    }

}
?>