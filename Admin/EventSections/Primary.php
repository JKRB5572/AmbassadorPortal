<table style='width: 100%;'>
    <tr>
        <th>Event Name</th>
        <td><input id="eventName" type="text" name="eventName" <?php if(isset($eventDetails["eventName"])){ fieldHasValue(decrypt($eventDetails["eventName"])); }?>></td>
    </tr>
    <tr>
        <th>Project</th>
        <td><input id="project" name="project" type="text" <?php if(isset($eventDetails["project"])){ fieldHasValue(decrypt($eventDetails["project"])); }?>></td>
    </tr>
    <tr>
        <th>Funding Source</th>
        <td>
            <select id="fundingSource" name="fundingSource">
                <option value="Institute of Coding" <?php if(isset($eventDetails["fundingSource"])){ fieldHasThisValue("Institute of Coding", decrypt($eventDetails["fundingSource"])); }?>>Institute of Coding</option>
                <option value="Technocamps" <?php if(isset($eventDetails["fundingSource"])){ fieldHasThisValue("Technocamps", decrypt($eventDetails["fundingSource"])); }?>>Technocamps</option>
                <option value="Cardiff University" <?php if(isset($eventDetails["fundingSource"])){ fieldHasThisValue("Cardiff University", decrypt($eventDetails["fundingSource"])); }?>>Cardiff University</option>
                <option value="Other" <?php if(isset($eventDetails["fundingSource"])){ fieldHasThisValue("Other", decrypt($eventDetails["fundingSource"])); }?>>Other</option>
            </select>
        </td>
    </tr>
    <tr>
        <th>Date</th>
        <td><input id="eventDate" name="eventDate" type="date" min="<?php echo todayDate; ?>" <?php if(isset($eventDetails["eventDate"])){ fieldHasValue($eventDetails["eventDate"]); }?>></td>
    </tr>
    <tr>
        <th>Start Time</th>
        <td><input id="startTime" name="startTime" type="time" step="300" value="09:00" <?php if(isset($eventDetails["startTime"])){ fieldHasValue($eventDetails["startTime"]); }?>></td>
    </tr>
    <tr>
        <th>End Time</th>
        <td><input id="endTime" name="endTime" type="time" step="300" value="16:00" <?php if(isset($eventDetails["endTime"])){ fieldHasValue($eventDetails["endTime"]); }?>></td>
    </tr>
    <tr>
        <th>Event Type</th>
        <td>

            <select id="eventType" name="eventType" onchange="eventTypeUpdates()">
                <option value="">---</option>
                <option value="Primary School" <?php if(isset($eventDetails["type"])){ fieldHasThisValue("Primary School", decrypt($eventDetails["type"])); }?>>Primary School</option>
                <option value="Secondary School" <?php if(isset($eventDetails["type"])){ fieldHasThisValue("Secondary School", decrypt($eventDetails["type"])); }?>>Secondary School</option>
                <option value="College" <?php if(isset($eventDetails["type"])){ fieldHasThisValue("College", decrypt($eventDetails["type"])); }?>>College</option>
                <option value="CPD" <?php if(isset($eventDetails["type"])){ fieldHasThisValue("CPD", decrypt($eventDetails["type"])); }?>>CPD</option>
                <option value="Community" <?php if(isset($eventDetails["type"])){ fieldHasThisValue("Community", decrypt($eventDetails["type"])); }?>>Community Event</option>
                <option value="CU Open Day" <?php if(isset($eventDetails["type"])){ fieldHasThisValue("CU Open Day", decrypt($eventDetails["type"])); }?>>Cardiff University Open Day</option>
                <option value="CU UCAS Day" <?php if(isset($eventDetails["type"])){ fieldHasThisValue("CU UCAS Day", decrypt($eventDetails["type"])); }?>>Cardiff Unviersity UCAS Day</option>
                <option value="Networking Event" <?php if(isset($eventDetails["type"])){ fieldHasThisValue("Networking Event", decrypt($eventDetails["type"])); }?>>Networking Event</option>
                <option value="Other" <?php if(isset($eventDetails["type"])){ fieldHasThisValue("Other", decrypt($eventDetails["type"])); }?>>Other</option>
            </select>

        </th>
    </tr>
</table>