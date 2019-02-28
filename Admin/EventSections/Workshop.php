<table style='width: 100%;'>
    <tr>
        <th>Number of Participants</th>
        <td><input id="numberOfParticipants" name="numberOfParticipants" oninput="updateNoAmbassadors(this.value)" type="number" min="0" max="250" step="10" placeholder="0" <?php if(isset($eventDetails["numberOfParticipants"])){ fieldHasValue($eventDetails["numberOfParticipants"]); }?>></td>
    </tr>
    <tr id="yearGroupRow" style="display: none;">
        <th>Year Group</th>
        <td>

            <table id="yearGroupPrimarySchool" class="event-checkbox">
                <tr>
                    <td><input id="yr1Checkbox" type='checkbox' name='yearGroup[]' value='Year 1' <?php if(isset($eventDetails["yearGroup"])){ fieldIsCheckedArray($eventDetails["yearGroup"], 'Year 1'); }?>></td>
                    <td>Year 1</td>
                    <td><input id="yr2Checkbox" type='checkbox' name='yearGroup[]' value='Year 2' <?php if(isset($eventDetails["yearGroup"])){ fieldIsCheckedArray($eventDetails["yearGroup"], 'Year 2'); }?>></td>
                    <td>Year 2</td>
                </tr>
                <tr>
                    <td><input id="yr3Checkbox" type='checkbox' name='yearGroup[]' value='Year 3' <?php if(isset($eventDetails["yearGroup"])){ fieldIsCheckedArray($eventDetails["yearGroup"], 'Year 3'); }?>></td>
                    <td>Year 3</td>
                    <td><input id="yr4Checkbox" type='checkbox' name='yearGroup[]' value='Year 4' <?php if(isset($eventDetails["yearGroup"])){ fieldIsCheckedArray($eventDetails["yearGroup"], 'Year 4'); }?>></td>
                    <td>Year 4</td>
                </tr>
                <tr>
                    <td><input id="yr5Checkbox" type='checkbox' name='yearGroup[]' value='Year 5' <?php if(isset($eventDetails["yearGroup"])){ fieldIsCheckedArray($eventDetails["yearGroup"], 'Year 5'); }?>></td>
                    <td>Year 5</td>
                    <td><input id="yr6Checkbox" type='checkbox' name='yearGroup[]' value='Year 6' <?php if(isset($eventDetails["yearGroup"])){ fieldIsCheckedArray($eventDetails["yearGroup"], 'Year 6'); }?>></td>
                    <td>Year 6</td>
                </tr>
            </table>

            <table id="yearGroupSecondarySchool" class="event-checkbox">
                <tr>
                    <td><input id="yr7Checkbox" type='checkbox' name='yearGroup[]' value='Year 7' <?php if(isset($eventDetails["yearGroup"])){ fieldIsCheckedArray($eventDetails["yearGroup"], 'Year 7'); }?>></td>
                    <td>Year 7</td>
                    <td><input id="yr8Checkbox" type='checkbox' name='yearGroup[]' value='Year 8' <?php if(isset($eventDetails["yearGroup"])){ fieldIsCheckedArray($eventDetails["yearGroup"], 'Year 8'); }?>></td>
                    <td>Year 8</td>
                </tr>
                <tr>
                    <td><input id="yr9Checkbox" type='checkbox' name='yearGroup[]' value='Year 9' <?php if(isset($eventDetails["yearGroup"])){ fieldIsCheckedArray($eventDetails["yearGroup"], 'Year 9'); }?>></td>
                    <td>Year 9</td>
                    <td><input id="yr10Checkbox" type='checkbox' name='yearGroup[]' value='Year 10' <?php if(isset($eventDetails["yearGroup"])){ fieldIsCheckedArray($eventDetails["yearGroup"], 'Year 10'); }?>></td>
                    <td>Year 10</td>
                </tr>
                <tr>
                    <td><input id="yr11Checkbox" type='checkbox' name='yearGroup[]' value='Year 11' <?php if(isset($eventDetails["yearGroup"])){ fieldIsCheckedArray($eventDetails["yearGroup"], 'Year 11'); }?>></td>
                    <td>Year 11</td>
                    <td><input id="yr12Checkbox" type='checkbox' name='yearGroup[]' value='Year 12' <?php if(isset($eventDetails["yearGroup"])){ fieldIsCheckedArray($eventDetails["yearGroup"], 'Year 12'); }?>></td>
                    <td>Year 12</td>
                </tr>
                <tr>
                    <td><input id="yr13Checkbox" type='checkbox' name='yearGroup[]' value='Year 13' <?php if(isset($eventDetails["yearGroup"])){ fieldIsCheckedArray($eventDetails["yearGroup"], 'Year 13'); }?>></td>
                    <td>Year 13</td>
                    <td></td>
                    <td></td>
                </tr>
            </table>

        </td>
    </tr>
    <tr id="levelRow" style="display: none;">
        <th>Level</th>
        <td>
            <select id="level" name="level">
                <option value="">---</option>
                <option value="Level 1" <?php if(isset($eventDetails["level"])){ fieldHasValue($eventDetails["level"], "Level 1"); }?>>Level 1</option>
                <option value="Level 2" <?php if(isset($eventDetails["level"])){ fieldHasValue($eventDetails["level"], "Level 2"); }?>>Level 2</option>
                <option value="Level 2" <?php if(isset($eventDetails["level"])){ fieldHasValue($eventDetails["level"], "Level 3"); }?>>Level 3</option>
                <option value="Level 4" <?php if(isset($eventDetails["level"])){ fieldHasValue($eventDetails["level"], "Level 4"); }?>>Level 4</option>
                <option value="Level 5" <?php if(isset($eventDetails["level"])){ fieldHasValue($eventDetails["level"], "Level 5"); }?>>Level 5</option>
            </select>
        </td>
    </tr>
    </tr>
    <tr>
        <th>Topic(s)</th>
        <td>
            <table id="eventTopics" class="event-checkbox">

                <?php

                $topics = sqlFetch("SELECT * FROM Topics ORDER BY topicName ASC", "ASSOC");

                for($i = 0; $i < count($topics); $i+=2){
                    ?>
                    <tr>
                        <td><input type='checkbox' name='eventTopic[]' value='<?php echo $topics[$i]["topicID"]?>' <?php if(isset($eventDetails["topic"])){ fieldIsCheckedArray($eventDetails["topic"], $topics[$i]["topicID"]); }?>></td>
                        <td><?php echo $topics[$i]["topicName"]; ?></td>

                        <?php

                        if(isset($topics[$i+1])){
                            ?>
                            <td><input type='checkbox' name='eventTopic[]' value='<?php echo $topics[$i+1]["topicID"]?>' <?php if(isset($eventDetails["topic"])){ fieldIsCheckedArray($eventDetails["topic"], $topics[$i+1]["topicID"]); }?>></td>
                            <td><?php echo $topics[$i+1]["topicName"]; ?></td>
                            <?php
                        }

                        else{
                            ?>
                            <td></td><td></td>
                            <?php
                        }

                        ?>

                    </tr>
                    <?php
                }


                ?>

            </table>
        </td>
    </tr>
</table>