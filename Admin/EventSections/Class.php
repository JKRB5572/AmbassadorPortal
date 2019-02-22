<table style='width: 100%;'>
    <tr>
        <th>Name</th>
        <td><input id="className" name="className" type="text" <?php if(isset($eventDetails["className"])){ fieldHasValue(decrypt($eventDetails["className"])); }?>></td>
    </tr>
    <tr>
        <th>Size</th>
        <td><input id="classSize" name="classSize" oninput="updateNoAmbassadors(this.value)" type="number" min="0" max="250" step="10" placeholder="0" <?php if(isset($eventDetails["classSize"])){ fieldHasValue($eventDetails["classSize"]); }?>></td>
    </tr>

        <?php

        if(isset($isEdit)){
            $eventType = sqlFetch("SELECT type FROM EventPrimary WHERE eventID = '".$eventID."'", "NUM");
            $eventType = decrypt($eventType[0][0]);

            if($eventType == "Primary School"){
                primarySchoolLevels();
            }
            elseif($eventType == "Secondary School"){
                secondarySchoolLevels();
            }
            elseif($eventType == "College"){
                collegeLevels();
            }
            elseif($eventType == "CPD" || $eventType == "Other"){
                AllLevels();
            }
        }
        else{
            levels();
        }

        ?>

    </tr>
    <tr>
        <th>Topic(s)</th>
        <td>
            <table class="event-checkbox">

                <?php

                $topics = sqlFetch("SELECT * FROM Topics ORDER BY topicName ASC", "ASSOC");

                for($i = 0; $i < count($topics); $i+=2){
                    ?>
                    <tr>
                        <td><input type='checkbox' name='eventTopic[]' value='<?php echo $topics[$i]["topicID"]?>' <?php if(isset($eventDetails["topic"])){ fieldIsCheckedArray($eventDetails["topic"], $topics[$i]["topicID"]); }?>></td>
                        <td><?php echo $topics[$i]["topicName"]; ?></td>
                        <td><input type='checkbox' name='eventTopic[]' value='<?php echo $topics[$i+1]["topicID"]?>' <?php if(isset($eventDetails["topic"])){ fieldIsCheckedArray($eventDetails["topic"], $topics[$i+1]["topicID"]); }?>></td>
                        <td><?php echo $topics[$i+1]["topicName"]; ?></td>
                    </tr>
                    <?php
                }


                ?>

            </table>
        </td>
    </tr>
</table>