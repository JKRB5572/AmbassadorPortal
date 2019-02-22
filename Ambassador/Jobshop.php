<?php

include "../PageComponents/Head.php";

?>

<h2>Jobshop Card</h2>

<div class="ambassador-jobshop">

<?php

$previousJobshopRejected = false;

$result = sqlFetch("SELECT jobshopConfirmed FROM Ambassadors WHERE universityID = '".$_SESSION["userID"]."'", "NUM");
$result = $result[0];


if($result[0] == 1){
    echo "<p>Jobshop status confirmed.</p>";
}

else{

    if($result[0] == -1){
        $previousJobshopRejected = true;
    }
    
    if( count( sqlFetch("SELECT * FROM Jobshop WHERE universityID = '".$_SESSION["userID"]."'", "ASSOC") ) > 0 ){
        $noImageExists = false;
    }
    else{
        $noImageExists = true;
    }

    $uploadFailed = false;

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        $checkIsImage = getimagesize($_FILES["jobshopImage"]["tmp_name"]);

        if($checkIsImage !== false){

            $imageFileType = strtolower(pathinfo(basename($_FILES["jobshopImage"]["name"]),PATHINFO_EXTENSION));


            if($imageFileType == "jpg" || $imageFileType == "jpeg") {

                $jobshopImage = addslashes(file_get_contents($_FILES["jobshopImage"]["tmp_name"]));

                if($noImageExists == false){

                    if(sqlUpdate("UPDATE Jobshop SET cardImage = '".$jobshopImage."' WHERE universityID = '".$_SESSION["userID"]."'", true, true) == false){
                        echo "<p style='color: red;'>Image upload was not sucessful - try uploading a smaller file.<p>";
                        $uploadFailed = true;
                    }

                }

                else{

                    if(sqlInsert("INSERT INTO Jobshop(universityID, cardImage) VALUES ('".$_SESSION["userID"]."', '".$jobshopImage."')", true, true) == false){
                        echo "<p style='color: red;'>Image upload was not sucessful - try uploading a smaller file.<p>";
                        $uploadFailed = true;
                    }
                    else{
                        $noImageExists = false;
                    }

                }

            }
            else{
                echo "<p style='color: red;'>The image you attempted to upload was not an .jpg image. Please upload a .jpg image</p>";
            }

        }
        else{
            echo "<p stlye='color: red;'>The file you attempted to upload was not an image. Please upload a valid file</p>";
        }

    }

    if($noImageExists == false && ($uploadFailed == false) ){
        $results = sqlFetch("SELECT cardImage FROM Jobshop WHERE universityID = '".$_SESSION["userID"]."'", "ASSOC");
        $results = $results[0]; //Remove outer array

        if($previousJobshopRejected == true && $_SERVER["REQUEST_METHOD"] == "POST"){
            sqlUpdate("UPDATE Ambassadors SET jobshopConfirmed = 0 WHERE universityID = '".$_SESSION["userID"]."'");

            echo "<p>This is the image you have uploaded. If you would like to change it please use the form below to upload a new image.</p><img src='data:image/jpg;base64,".base64_encode($results['cardImage'])."' />";
        }

        elseif($previousJobshopRejected == true){
            echo "<p style='color: red;'>Your JobShop Card (shown below) was rejected as an invalid. Please upload a new image of your valid JobShop Card. If you continue to run into problems please contact an admin at comscambassadors@cardiff.ac.uk</p><img src='data:image/jpg;base64,".base64_encode($results['cardImage'])."' />";
        }
    
        else{
            echo "<p>This is the image you have uploaded. If you would like to change it please use the form below to upload a new image.</p><img src='data:image/jpg;base64,".base64_encode($results['cardImage'])."' />";
        }
    }

    ?>

<p>Please take a photo of your JobShop card and upload it using the form below. Once uploaded an administrator will confirm your Jobshop status and you will be to access the portal.<br/><em>Only '.jpg' images are supported.</em></p>

<form method="POST" action="Jobshop.php" enctype="multipart/form-data">
    <div style='text-align: center;'><input type="file" name="jobshopImage"></div>
    <div style='text-align: center;'><input type="submit"></div>
</form>

</div><!-- "ambassador-jobshop" -->


<?php

}

include "../PageComponents/Foot.php";

?>