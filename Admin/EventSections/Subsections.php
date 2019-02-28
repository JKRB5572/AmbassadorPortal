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