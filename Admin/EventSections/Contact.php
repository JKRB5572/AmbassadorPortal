<table style="width: 100%;">
    <tr>
        <th>Name</th>
        <td><input id="contactName" name="contactName" type="text" <?php if(isset($eventDetails["name"])){ fieldHasValue(decrypt($eventDetails["name"])); }?>></td>
    </tr>
    <tr>
        <th>Email Address</th>
        <td><input id="contactEmail" name="contactEmail" type="email" <?php if(isset($eventDetails["email"])){ fieldHasValue(decrypt($eventDetails["email"])); }?>></td>
    </tr>
    <tr>
        <th>Phone Number</th>
        <td><input id="contactPhoneNo" name="contactPhoneNo" type="tel" <?php if(isset($eventDetails["phoneNo"])){ fieldHasValue(decrypt($eventDetails["phoneNo"])); }?>></td>
    </tr>
</table>