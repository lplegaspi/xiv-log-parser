<?php
if (is_array($encounters) && count($encounters)) {
    echo '<table border=1 cellpadding=5 style="border-collapse:collapse;">';
        echo '<tr>';
            echo '<th>ID</th>';
            echo '<th>FIGHT NAME</th>';
            echo '<th>DURATION</th>';
            echo '<th>LINKS</th>';
        echo '</tr>';
        foreach($encounters['fights'] as $key => $encounter) {
            $duration        = StringFormatter::getDurationFromStartAndEndTime($encounter['start_time'], $encounter['end_time']);
            $httpQuery       = StringFormatter::getEncounterHttpQueryByParams($_GET['reportId'], $encounter['start_time'], $encounter['end_time']);
            $classKillStatus = isset($encounter['kill']) && $encounter['kill'] ? 'cleared' : null;

            echo '<tr class="'.$classKillStatus.'">';
                echo '<td>'.$encounter['id'].'</td>';
                echo '<td>'.$encounter['name'].'</td>';
                echo '<td>'.$duration.'</td>';
                echo '<td>';
                    echo '<a href="' . $httpQuery . '">Damage Taken</a>';
                echo '</td>';
            echo '</tr>';
        }
    echo '</table>';
} else {
    echo 'The report ID you have provided doesn\'t seem to contain any valid encounters.';
    echo '<br/>Check another report?';

    require_once('views/_reportIdForm.php');
}
