<?php require_once('views/_encounterDetails/_navigation.php'); ?>
<table class="encounterEvents" border=1 cellpadding=5>
    <thead>
        <tr>
            <th width="100">SOURCE</th>
            <th width="200">ABILITY</th>
            <th width="100">TARGET</th>
            <th width="50">DAMAGE</th>
            <th>TIME</th>
            <th width="50">HEALED</th>
            <th width="100">TARGET</th>
            <th width="200">ABILITY</th>
            <th width="100">SOURCE</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <?php
                foreach($encounterEvents['events'] as $key => $event){
                    switch($event['type']){
                        case 'damage':
                            echo '<tr>';
                                echo '<td>' . ReportParser::getSourceNameOfEvent($event, $participants) . '</td>';
                                echo '<td>' . $event["ability"]["name"] . '</td>';
                                echo '<td>' . $participants[$event["targetID"]]['name'] . '</td>';
                                echo '<td>' . $event["amount"] . '</td>';
                                echo '<td>' . StringFormatter::getDurationFromStartAndEndTime($_GET['startTime'], $event['timestamp']) . '</td>';

                                echo '<td class="no-data">-</td>';
                                echo '<td class="no-data">-</td>';
                                echo '<td class="no-data">-</td>';
                                echo '<td class="no-data">-</td>';
                            echo '</tr>';
                        break;
                        case 'heal':
                            echo '<tr>';
                                echo '<td class="no-data">-</td>';
                                echo '<td class="no-data">-</td>';
                                echo '<td class="no-data">-</td>';
                                echo '<td class="no-data">-</td>';

                                echo '<td>' . StringFormatter::getDurationFromStartAndEndTime($_GET['startTime'], $event['timestamp']) . '</td>';
                                echo '<td>' . $event["amount"] . '</td>';
                                echo '<td>' . $participants[$event["targetID"]]['name'] . '</td>';
                                echo '<td>' . $event["ability"]["name"] . '</td>';
                                echo '<td>' . ReportParser::getSourceNameOfEvent($event, $participants) . '</td>';
                            echo '</tr>';
                        break;
                    }
                }
            ?>
        </tr>
    </tbody>
</table>
