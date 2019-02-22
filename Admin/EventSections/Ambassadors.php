<table style="width: 100%;">
    <tr>
        <th>Number Needed</th>
        <td><input id="numberNeeded" name="numberNeeded" type="number" min="1" max="20" placeholder="0" <?php if(isset($eventDetails["numberNeeded"])){ fieldHasValue($eventDetails["numberNeeded"]); }?>></td>
    </tr>
    <tr id="trainingRequiredRow">
        <?php 
        
        if(isset($isEdit)){
            trainingRequiredEdit();
        }
        else{
            trainingRequired(); 
        }

        ?>
    </tr>
    <tr>
        <th>Lead Ambassador</th>
        <td>

            <select id="leadAmbassador" name="leadAmbassador">
                <option value="">---</option>
                <?php

                $selectOptions = sqlFetch("SELECT Ambassadors.forename AS forename, Ambassadors.surname as surname, Ambassadors.givenName as givenName, universityID FROM Ambassadors, Admin WHERE Ambassadors.universityID = Admin.adminID", "ASSOC");

                foreach($selectOptions as &$row){
                    $row["surname"] = decrypt($row["surname"]);
                    $row["forename"] = decrypt($row["forename"]);
                    $row["givenName"] = decrypt($row["givenName"]);
                }

                $surname = array_column($selectOptions, "surname");

                array_multisort($surname, SORT_ASC, $selectOptions);

                foreach($selectOptions as $option){

                    ?><option value='<?php echo $option["universityID"]; ?>' <?php if(isset($eventDetails["leadAmbassador"])){ fieldHasThisValue($eventDetails["leadAmbassador"], $option["universityID"]); }?>>

                    <?php
                    
                    echo $option["forename"];
                    if($option["givenName"] != null){
                        echo " (".$option["givenName"].")";
                    }
                    echo " ".$option["surname"]."</option>";
                }

                ?>
            </select>
                
        </td>
    </tr>
    <tr>
        <th>Report Location</th>
        <td><input id="reportLocation" name="reportLocation" type="text" value="COMSC Reception" <?php if(isset($eventDetails["reportLocation"])){ fieldHasValue($eventDetails["reportLocation"]); }?>></td>
    </tr>
</table>