<?php

include "../PageComponents/Head.php";

?>

<h2>Site Metrics</h2>

<div class="developer">

<?php

$allMetrics = sqlFetch("SELECT * FROM Metrics", "ASSOC");

?>

<table>
    <tr>
        <th>Metric</th>
        <th>Value</th>
    </tr>

    <?php
    
    foreach($allMetrics as $metric){
        echo "
        <tr>
            <td>".$metric["metricName"]."</td>
            <td>".$metric["value"]."</td>
        </tr>";
    }

    ?>

</table>