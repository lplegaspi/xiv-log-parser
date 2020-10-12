<?php require_once('views/_encounterDetails/_navigation.php'); ?>
<table class="encounterEvents w100p" border=1 cellpadding=5>
    <thead>
        <tr>
            <th width="12%">SOURCE</th>
            <th width="16%">ABILITY</th>
            <th width="12%">TARGET</th>

            <th width="8%">DAMAGE</th>
            <th width="4%">TIME</th>
            <th width="8%">HEALED</th>

            <th width="12%">TARGET</th>
            <th width="16%">ABILITY</th>
            <th width="12%">SOURCE</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <?php
                foreach($events as $key => $event){
                    switch($event['type']){
                        case 'damage':
                            echo '<tr>';
                                echo '<td>' . ReportParser::getSourceNameOfEvent($event, $participants) . '</td>';
                                echo '<td>' . $event["ability"]["name"] . '</td>';
                                echo '<td>' . $participants[$event["targetID"]]['name'] . '</td>';
                                echo '<td>' . $event["amount"] . '</td>';
                                echo '<td>' . StringFormatter::getDurationFromStartAndEndTime($startEndTime['startTime'], $event['timestamp']) . '</td>';

                                echo '<td class="no-data">-</td>'
                                    . '<td class="no-data">-</td>'
                                    . '<td class="no-data">-</td>'
                                    . '<td class="no-data">-</td>'
                                ;
                            echo '</tr>';
                        break;
                        case 'heal':
                            echo '<tr>';
                                echo '<td class="no-data">-</td>'
                                    . '<td class="no-data">-</td>'
                                    . '<td class="no-data">-</td>'
                                    . '<td class="no-data">-</td>'
                                ;

                                echo '<td>' . StringFormatter::getDurationFromStartAndEndTime($startEndTime['startTime'], $event['timestamp']) . '</td>';
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
