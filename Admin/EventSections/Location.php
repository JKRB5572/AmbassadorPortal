<table style='width: 100%;'>
    <tr>
        <th>Address Line 1</th>
        <td><input id="address1" name="address1" type="text" <?php if(isset($eventDetails["address1"])){ fieldHasValue(decrypt($eventDetails["address1"])); }?>></td>
    </tr>
    <tr>
        <th>Address Line 2</th>
        <td><input id="address2" name="address2" type="text" <?php if(isset($eventDetails["address2"])){ fieldHasValue(decrypt($eventDetails["address2"])); }?>></td>
    </tr>
    <tr>
        <th>County / Borough</td>
        <td><input id="county" name="county" type="text" <?php if(isset($eventDetails["county"])){ fieldHasValue(decrypt($eventDetails["county"])); }?>></td>
    </tr>
    <tr>
        <th>Postcode</th>
        <td><input id="postcode" name="postcode" type="county" <?php if(isset($eventDetails["postcode"])){ fieldHasValue(decrypt($eventDetails["postcode"])); }?>></td>
    </tr>
    <tr>
        <th>Transport</th>
        <td>
            <select id="transport" name="transport">
                <option value="None" <?php if(isset($eventDetails["transport"])){ fieldHasThisValue($eventDetails["transport"], "None"); }?>>None</option>
                <option value="Car" <?php if(isset($eventDetails["transport"])){ fieldHasThisValue($eventDetails["transport"], "Car"); }?>>Car</option>
                <option value="Taxi" <?php if(isset($eventDetails["transport"])){ fieldHasThisValue($eventDetails["transport"], "Taxi"); }?>>Taxi</option>
                <option value="Train" <?php if(isset($eventDetails["transport"])){ fieldHasThisValue($eventDetails["transport"], "Train"); }?>>Train</option>
            </select>
        </td>
    </tr>
</table>