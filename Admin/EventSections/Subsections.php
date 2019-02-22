<?php

function trainingRequired(){
    ?>
    <th>Training Required</th>
    <td>
        <input name="trainingRequired" type="radio" value="Y" checked="checked">Yes
        <input name="trainingRequired" type="radio" value="P">Preferred
        <input name="trainingRequired" type="radio" value="N">No
    </td>

    <?php
}


function trainingRequiredEdit(){
    global $eventDetails;
    ?>
    <th>Training Required</th>
    <td>
        <input name="trainingRequired" type="radio" value="Y" <?php if(isset($eventDetails["trainingRequired"])){ fieldIsChecked($eventDetails["trainingRequired"], "Y"); }?>>Yes
        <input name="trainingRequired" type="radio" value="P" <?php if(isset($eventDetails["trainingRequired"])){ fieldIsChecked($eventDetails["trainingRequired"], "P"); }?>>Preferred
        <input name="trainingRequired" type="radio" value="N" <?php if(isset($eventDetails["trainingRequired"])){ fieldIsChecked($eventDetails["trainingRequired"], "N"); }?>>No
    </td>
    
    <?php
}


function eventVisibility(){
    ?>
    <tr>
        <th>Visibility</th>
        <td>
            <select id="visibility" name="visibility">
                <option value="2">Admins Only</option>
            </select>
        </td>
    </tr>

    <?php
}


function levels(){
    ?>
    <tr>
        <th>Level</th>
        <td><select id="level" name="level"></select></td>
    </tr>

    <?php
}


function primarySchoolLevels(){
    ?>
    <tr>
        <th>Level</th>
        <td>
            <select id="level" name="level">
                <option value="">---</option>
                <option value="KS1" <?php if(isset($eventDetails["level"])){ fieldHasValue($eventDetails["level"], "KS1"); }?>>KS1</option>
                <option value="KS2 (Basic)" <?php if(isset($eventDetails["level"])){ fieldHasValue($eventDetails["level"], "KS2 (Basic)"); }?>>KS2 (Basic)</option>
                <option value="KS2 (Advanced)" <?php if(isset($eventDetails["level"])){ fieldHasValue($eventDetails["level"], "KS2 (Advanced)"); }?>>KS2 (Advanced)</option>
            </select>
        </td>
    </tr>

    <?php
}


function secondarySchoolLevels(){
    ?>
    <tr>
        <th>Level</th>
        <td>
            <select id="level" name="level">
                <option value="">---</option>
                <option value="KS3" <?php if(isset($eventDetails["level"])){ fieldHasValue($eventDetails["level"], "KS3"); }?>>KS3</option>
                <option value="KS4" <?php if(isset($eventDetails["level"])){ fieldHasValue($eventDetails["level"], "KS4"); }?>>KS4</option>
                <option value="KS5" <?php if(isset($eventDetails["level"])){ fieldHasValue($eventDetails["level"], "KS5"); }?>>KS5</option>
            </select>
        </td>
    </tr>

    <?php
} 


function collegeLevels(){
    ?>
    <tr>
        <th>Level</th>
        <td>
            <select id="level" name="level">
                <option value="">---</option>
                <option value="Level 2" <?php if(isset($eventDetails["level"])){ fieldHasValue($eventDetails["level"], "Level 2"); }?>>Level 2</option>
                <option value="Level 2" <?php if(isset($eventDetails["level"])){ fieldHasValue($eventDetails["level"], "Level 3"); }?>>Level 3</option>
                <option value="Level 4" <?php if(isset($eventDetails["level"])){ fieldHasValue($eventDetails["level"], "Level 4"); }?>>Level 4</option>
                <option value="Level 5" <?php if(isset($eventDetails["level"])){ fieldHasValue($eventDetails["level"], "Level 5"); }?>>Level 5</option>
            </select>
        </td>
    </tr>

    <?php
}


function allLevels(){
    ?>
    <tr>
        <th>Level</th>
        <td>
            <select id="level" name="level">
                <option value="">---</option>
                <option value="KS2 (Basic)" <?php if(isset($eventDetails["level"])){ fieldHasValue($eventDetails["level"], "KS2 (Basic)"); }?>>KS2 (Basic)</option>
                <option value="KS2 (Advanced)" <?php if(isset($eventDetails["level"])){ fieldHasValue($eventDetails["level"], "KS2 (Advanced)"); }?>>KS2 (Advanced)</option>
                <option value="KS3" <?php if(isset($eventDetails["level"])){ fieldHasValue($eventDetails["level"], "KS3"); }?>>KS3</option>
                <option value="KS4" <?php if(isset($eventDetails["level"])){ fieldHasValue($eventDetails["level"], "KS4"); }?>>KS4</option>
                <option value="KS5" <?php if(isset($eventDetails["level"])){ fieldHasValue($eventDetails["level"], "KS5"); }?>>KS5</option>
                <option value="Level 2" <?php if(isset($eventDetails["level"])){ fieldHasValue($eventDetails["level"], "Level 2"); }?>>Level 2</option>
                <option value="Level 3" <?php if(isset($eventDetails["level"])){ fieldHasValue($eventDetails["level"], "Level 3"); }?>>Level 3</option>
                <option value="Level 4" <?php if(isset($eventDetails["level"])){ fieldHasValue($eventDetails["level"], "Level 4"); }?>>Level 4</option>
                <option value="Level 5" <?php if(isset($eventDetails["level"])){ fieldHasValue($eventDetails["level"], "Level 5"); }?>>Level 5</option>
            </select>
        </td>
    </tr>

    <?php
}


function eventRepeat(){
    ?>

    <tr>
        <th>Repeat Event</th>
        <td>
            <select id="repeatFrequency" name="repeatFrequency" onchange="showNumberOfRepeats()">
                <option value="">None</option>
                <option value="daily">Daily</option>
                <option value="weekly">Weekly</option>
                <option value="fortnightly">Fortnightly</option>
            </select>
        </td>
    </tr>
    <tr id="numberOfEventsRow">
        <th>Number of Events</th>
        <td><input name="numberOfEvents" type="number" min=2 max=52 value=2></td>
    </tr>

    <?php
}