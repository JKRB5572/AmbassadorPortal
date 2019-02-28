<?php

//Primary
if(isset($_POST["eventName"])){ $eventName = encrypt(validateInput($_POST["eventName"])); } else { $eventName = ""; }
if(isset($_POST["project"])){ $project = encrypt(validateInput($_POST["project"])); } else { $project = ""; }
if(isset($_POST["fundingSource"])){ $fundingSource = encrypt(validateInput($_POST["fundingSource"])); } else { $fundingSource = ""; }
if(isset($_POST["eventDate"])){ $eventDate = validateInput($_POST["eventDate"]); } else { $eventDate = ""; }
if(isset($_POST["startTime"])){ $startTime = validateInput($_POST["startTime"]); } else { $startTime = ""; }
if(isset($_POST["endTime"])){ $endTime = validateInput($_POST["endTime"]); } else { $endTime = ""; }
if(isset($_POST["eventType"])){ $eventType = encrypt(validateInput($_POST["eventType"])); } else { $eventType = ""; }
if(isset($_POST["adminID"])){ $adminID = validateInput($_POST["adminID"]); } else { $adminID = ""; }

//Location
if(isset($_POST["address1"])){ $address1 = encrypt(validateInput($_POST["address1"])); } else { $address1 = ""; }
if(isset($_POST["address2"])){ $address2 = encrypt(validateInput($_POST["address2"])); } else { $address2 = ""; }
if(isset($_POST["county"])){ $county = encrypt(validateInput($_POST["county"])); } else { $county = ""; }
if(isset($_POST["postcode"])){ $postcode = encrypt(validateInput($_POST["postcode"])); } else { $postcode = ""; }
if(isset($_POST["transport"])){ $transport = validateInput($_POST["transport"]); } else { $transport = ""; }

//Class
if(isset($_POST["yearGroup"])){ $yearGroup = $_POST["yearGroup"]; } else { $yearGroup = ""; }
if(isset($_POST["numberOfParticipants"])){ $numberOfParticipants = validateInput($_POST["numberOfParticipants"]); } else { $numberOfParticipants = ""; }
if(isset($_POST["level"])){ $level = validateInput($_POST["level"]); } else { $level = ""; }
if(isset($_POST["eventTopic"])){ $eventTopic = $_POST["eventTopic"]; } else { $eventTopic = ""; }

//Ambassadors
if(isset($_POST["numberNeeded"])){ $numberNeeded = validateInput($_POST["numberNeeded"]); } else { $numberNeeded = ""; }
if(isset($_POST["trainingRequired"])){ $trainingRequired = validateInput($_POST["trainingRequired"]); } else { $trainingRequired = ""; }
if(isset($_POST["leadAmbasador"])){ $leadAmbassador = validateInput($_POST["leadAmbassador"]); } else { $leadAmbassador = ""; }
if(isset($_POST["reportLocation"])){ $reportLocation = validateInput($_POST["reportLocation"]); } else { $reportLocation = ""; }

//Contact
if(isset($_POST["contactName"])){ $contactName = encrypt(validateInput($_POST["contactName"])); } else { $contactName = ""; }
if(isset($_POST["contactEmail"])){ $contactEmail = encrypt(validateInput($_POST["contactEmail"])); } else { $contactEmail = ""; }
if(isset($_POST["contactPhoneNo"])){ $contactPhoneNo = encrypt(validateInput($_POST["contactPhoneNo"])); } else { $contactPhoneNo = ""; }

//Additional Information
if(isset($_POST["visibility"])){ $visibility = validateInput($_POST["visibility"]); } else { $visibility = ""; }
if(isset($_POST["resourcesRequired"])){ $resourcesRequired = encrypt(validateInput($_POST["resourcesRequired"])); } else { $resourcesRequired = ""; }
if(isset($_POST["additionalInformation"])){ $additionalInformation = encrypt(validateInput($_POST["additionalInformation"])); } else { $additionalInformation = ""; }
if(isset($_POST["repeatFrequency"])){ $repeatFrequency = validateInput($_POST["repeatFrequency"]); } else { $repeatFrequency = ""; }
if(isset($_POST["numberOfEvents"])){ $numberOfEvents = validateInput($_POST["numberOfEvents"]); } else { $numberOfEvents = ""; }
if(isset($_POST["repeatPosition"])){ $repeatPosition = validateInput($_POST["repeatPosition"]); } else { $repeatPosition = 0; }

?>