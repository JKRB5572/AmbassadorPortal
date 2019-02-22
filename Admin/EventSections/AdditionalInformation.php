<table style="width: 100%;">

    <?php

    if(!isset($isEdit)){
        eventVisibility();
    }

    ?>
    <tr>
        <th>Resources Required</th>
        <td><input id="resourcesRequired" name="resourcesRequired" type="text" <?php if(isset($eventDetails["resourcesRequired"])){ fieldHasValue(decrypt($eventDetails["resourcesRequired"])); }?>></td>
    <tr>
        <th>Additional Information</th>
        <td><input id="additionalInformation" name="additionalInformation" type="text" <?php if(isset($eventDetails["additionalInformation"])){ fieldHasValue(decrypt($eventDetails["additionalInformation"])); }?>>
    </tr>

    <?php

    if(!isset($isEdit)){
        eventRepeat();
    }

    ?>

</table> 