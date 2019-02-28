<?php

include "../PageComponents/Head.php";

checkPageIsActive("AddAmbassador");


if($_SERVER["REQUEST_METHOD"] == "POST"){

    //Add single ambassador
    if(validateInput($_POST["postType"]) == "single"){

        $_universityID = validateInput($_POST["universityID"]);
        $universityID = encryptUsername($_universityID);

        $userExists = sqlFetch("SELECT universityID FROM Ambassadors WHERE universityID = '".$universityID."'", "NUM");

        if(count($userExists) > 0){
            $errorsAddSingleAmbassador = "A user with university ID {$_universityID} already exists";
        }
        else{

            $email = encrypt(strtolower(validateInput($_POST["email"])));
            $surname = encrypt(validateInput($_POST["surname"]));
            $forename = encrypt(validateInput($_POST["forename"]));
            $givenName = encrypt(validateInput($_POST["givenName"]));
            $programOfStudy = validateInput($_POST["programOfStudy"]);
            $yearOfStudy = validateInput($_POST["yearOfStudy"]);
            $driver = validateInput($_POST["driver"]);
            $tShirtSize = validateInput($_POST["tShirtSize"]);

            $query = "INSERT INTO Ambassadors(universityID, email, surname, forename, programOfStudy, yearOfStudy, driver, tShirtSize) VALUES ('{$universityID}', '{$email}', '{$surname}', '{$forename}', '{$programOfStudy}', '{$yearOfStudy}', '{$driver}', '{$tShirtSize}')";

            if(sqlInsert($query, True, True) == False){
                logSystemError("/Admin/AddAmbassador (Single)", "Uable to execute sql insert with query: {$query}");
                $errorsAddSingleAmbassador = "A system error has occured whilst attempting to write to the database. Please seek help from the system adminstrator and quote the following error code: 5-P-AA-0.";
            }
            else{
                $addSingleAmbassadorSuccess = true;
            }

        }

    }

    //Add multiple ambassadors
    else if(validateInput($_POST["postType"]) == "multiple"){

        $errorsAddMultipleAmbassadors = "";

        //Check CSV File is set
        if(isset($_FILES["csvFile"])){

            //Check no errors with file
            if($_FILES["csvFile"]["error"] > 0){
                $errorsAddMultipleAmbassadors = "Error with file: ".$_FILES["csvFile"]["error"];
            }
            else{
                $imageFileType = strtolower(pathinfo(basename($_FILES["csvFile"]["name"]),PATHINFO_EXTENSION));

                //Check file extension
                if($imageFileType == "csv" || $imageFIleType == "xlsx"){

                    $row = 0;

                    if( ($handle = fopen($_FILES["csvFile"]["tmp_name"], 'r+')) !== FALSE ){
                        while( ($data = fgetcsv($handle, 1000, ",")) !== FALSE){
                            $row++;

                            //Check correct amount of data is present
                            if(count($data) != 9){
                                $errorsAddMultipleAmbassadors .= "Error in row {$row}: row size smaller than expected.<br/>";
                            }
                            else{
                                $errorsWithRow = 0;

                                //Collect data
                                $universityID = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $data[0]);

                                
                                if(strlen($universityID) == 8){
                                    $universityID = substr($universityID, 1);
                                }

                                //Check student number is 7 digits
                                if(preg_match("/^[0-9]{7}$/", $universityID)){

                                    $universityID = encryptUsername($universityID);

                                    $userExists = sqlFetch("SELECT universityID FROM Ambassadors WHERE universityID = '{$universityID}'", "NUM");

                                    if(count($userExists) > 0){
                                        $errorsWithRow++;
                                        $errorsAddMultipleAmbassadors .= "Error in row {$row}: A user with university ID ".$data[0]." already exists.<br/>";
                                    }
                                }
                                else{
                                    $errorsWithRow++;
                                    $errorsAddMultipleAmbassadors .= "Error in row {$row}: ".$data[0]." is an invalid university ID.<br/>";
                                }

                                //Check email address
                                $email = strtolower($data[1]);
                                if(!preg_match("/^[a-z0-9]+(|@cardiff.ac.uk)$/", $email)){
                                    $errorsWithRow++;
                                    $errorsAddMultipleAmbassadors .= "Error in row {$row}: ".$data[1]." is an invalid email address.<br/>";
                                }
                                else if(preg_match("/^[a-z0-9]+$/", $email)){
                                    $email = encrypt($email."@cardiff.ac.uk");
                                }
                                else{
                                    $email = encrypt($email);
                                }

                                //Check surname
                                if(!preg_match("/^[A-Z]([a-z]+|[a-z]+\-[A-Z][a-z]+)$/", $data[2])){
                                    $errorsWithRow++;
                                    $errorsAddMultipleAmbassadors .= "Error in row {$row}: ".$data[2]." is an invalid surname.<br/>";
                                }
                                else{
                                    $surname = encrypt($data[2]);
                                }

                                //Check forename
                                if(!preg_match("/^[A-Z][a-z]+(|(\ [A-Z][a-z]+)+)$/", $data[3])){
                                    $errorsWithRow++;
                                    $errorsAddMultipleAmbassadors .= "Error in row {$row}: ".$data[3]." is an invalid forename.<br/>";
                                }
                                else{
                                    $forename = encrypt($data[3]);
                                }

                                //Check givenName
                                if(!preg_match("/^[A-Z][a-z]+$/", $data[4])){
                                    $givenName = "";
                                }
                                else{
                                    $givenName = encrypt($data[4]);
                                }

                                //Check program of study
                                if(strlen($data[5]) > 4){
                                    $errorsWithRow++;
                                    $errorsAddMultipleAmbassadors .= "Error in row {$row}: course code must be less than four digits";
                                }
                                else{
                                    $programOfStudy = $data[5];
                                }

                                //Check year of study
                                if(!preg_match("/^[1-5]$/", $data[6])){
                                    $errorsWithRow++;
                                    $errorsAddMultipleAmbassadors .= "Error in row {$row}: year of study must be within the range 1-5";
                                }
                                else{
                                    $yearOfStudy = $data[6];
                                }

                                //Check driver
                                if($data[7] == "1"){
                                    $driver = $data[7];
                                }
                                else{
                                    $driver = "0";
                                }

                                //Check t-shirt size
                                if(preg_match("/^(XS|S|M|L|XL)$/", $data[8])){
                                    $tShirtSize = $data[8];
                                }
                                else{
                                    $tShirtSize = "L";
                                }

                                
                                //check no errors in row
                                if($errorsWithRow == 0){

                                    $query = "INSERT INTO Ambassadors(universityID, email, surname, forename, programOfStudy, yearOfStudy, driver, tShirtSize) VALUES ('{$universityID}', '{$email}', '{$surname}', '{$forename}', '{$programOfStudy}', '{$yearOfStudy}', '{$driver}', '{$tShirtSize}')";

                                    if(sqlInsert($query, True, True) == False){
                                        logSystemError("/Admin/AddAmbassador (Single)", "Uable to execute sql insert with query: {$query}");
                                        $errorsAddMultipleAmbassadors = "A system error has occured whilst attempting to write to the database. Please seek help from the system adminstrator and quote the following error code: 5-P-AA-0.";
                                    }

                                }
                        

                            }

                        }
                    }

                }

            }
        }
        else{
            $errorsAddMultipleAmbassadors = "ERROR: No file selected.";
        }

    }
}

?>

<script>

function validateSubmit(){
    var errorText = "";
    
    var id = document.getElementById("unviersityID").value;
    if(id.length == 8){
        document.getElementById("unviersityID").value = substring(document.getElementById("unviersityID").value, 1);
    }
    if(!id.match(/^[0-9]{7}$/)){
        errorText += "Please enter a valid universityID<br/>";
    }

    var email = document.getElementById("email").value;
    if(!email.match(/^[a-zA-Z0-9]+(|@cardiff.ac.uk)$/)){
        errorText += "Please enter a valid Cardiff University emaill address<br/>";
    }
    else if(!email.match(/@cardiff.ac.uk)$/)){
        document.getElementById("email").value = document.getElementById("email").value + "@cardiff.ac.uk";
    }

    var surname = document.getElementById("surname").value;
    if(!surname.match(/^[A-Z][a-z]+(|\-[A-Z][a-z]+)$/)){
        errorText += "Please enter a valid surname<br/>";
    }

    var forename = document.getElementById("forename").value;
    if(!forename.match(/^[A-Z][a-z]+(|(\ [A-Z][a-z]+)+)$/)){
        errorText += "Please enter a valid forename<br/>";
    }

    var givenName = document.getElementById("givenName").value;
    if(givenName != ""){
        if(!givenName.match(/^[A-Z][a-z]+$/)){
            errorText += "Please enter a valid given name<br/>";
        }
    }

    if(errorText != ""){
        document.getElementById("errorOutput").innerHTML = errorText;
    }
    else{
        var form = document.getElementById("addSingleAmbassador");
        form.submit();
    }
}

</script>

<h2>Add Ambassador</h2>

<div class="two-column-layout-left">

    <h4>Add Single Ambassador</h4>

    <?php
    
    if(isset($addSingleAmbassadorSuccess)){
        echo "<p style='color: green;'><strong>Ambassador added successfully.</strong></p>";
    }
    else if(isset($errorsAddSingleAmbassador)){
        echo "<p style='color: red;'><strong>{$errorsAddSingleAmbassador}</strong></p>";
    }

    ?>

    <div class="add-ambassador-form">
        <p id="errorOutput" style="color: red;"></p>
        <form id="addSingleAmbassador" method="POST" onkeypress="return event.keyCode != 13;">
        <input name="postType" value="single" style="display: none;">
        <table>
            <tr>
                <th>University ID</th>
                <td><input id="unviersityID" name="universityID" type="text"  maxlength="7"></td>
            </tr>
            <tr>
                <th>Cardiff Email Address</th>
                <td><input id="email" name="email" type="text"></td>
            </tr>
            <tr>
                <th>Surname</th>
                <td><input id="surname" name="surname" type="text"></td>
            </tr>
            <tr>
                <th>Forename</th>
                <td><input id="forename" name="forename" type="text"></td>
            </tr>
            <tr>
                <th>Given Name</th>
                <td><input id="givenName" name="givenName" type="text"></td>
            </tr>
            <tr>
                <th>Program Of Study</th>
                <td><select id="programOfStudy" name="programOfStudy">
                        <option value="4JVD">Applied Software Engineering (BSc)</option>
                        <option value="G400">Computer Science (BSc)</option>
                        <option value="G401">Computer Science with a Year in Industry (BSc)</option>G404
                        <option value="G404">Computer Science (MSci)</option>
                        <otpion value="CSPD">Computer Science PhD</option>
                        <option value="OTHR">Other Degree Scheme</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th>Year of Study</th>
                <td><input id="yearOfStudy" name="yearOfStudy" type="number" min="1" max="5" value="1"></td>
            </tr>
            <tr>
                <th>Driver</th>
                <td>
                    <select id="driver" name="driver">
                        <option value="0">No</option>
                        <option value="1">Yes</option>
                    </select>
                </td>
            </tr>
            <tr>
                
                <th>T-Shirt Size</th>
                <td>
                    <select di="tShirtSize" name="tShirtSize"> 
                        <option value="XS">XS</option>
                        <option value="S">S</option>
                        <option value="M">M</option>
                        <option value="L">L</option>
                        <option value="XL">XL</otpion>
                    </select>
                </td>
            </tr>
        </table>
    </form>
    <button class="server-action" onclick="validateSubmit()">Add Ambassador

    </div><!-- add-ambassador-form -->
</div><!-- two-column-layout-left -->

<div class="two-column-layout-right">
    <h4>Add Multiple Ambassdors</h4>

    <?php
    
    if(isset($errorsAddMultipleAmbassadors)){
        if($errorsAddMultipleAmbassadors == ""){
            echo "<p style='color: green;'><strong>Ambassadors added</strong></p>";
        }
        else{
            echo "<p style='color: red;'><strong>{$errorsAddMultipleAmbassadors}</strong></p>";
        }
    }

    ?>
    <p>To add multiple ambassadors please upload a CSV file below in the following format; each row contains university ID, email address, surname, forename, given name, course code, year of study, driver, T-shirt size in that order with no column headings</p>

    <form method="POST" accept-charset="utf-8" enctype="multipart/form-data" onkeypress="return event.keyCode != 13;">
        <input name="postType" value="multiple" style="display: none;">
        <input type="file" name="csvFile"><br/>
        <button class="server-action">Add Ambassadors
    </form>

</div><!-- two-column-layout-right -->

<?php

include "../PageComponents/Foot.php";

?>