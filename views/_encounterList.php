<?php
if($reportParser->hasEncounters()){
    echo '<table border=1 cellpadding=5 style="border-collapse:collapse;">';
        echo '<tr>';
            echo '<th>ID</th>';
            echo '<th>FIGHT NAME</th>';
            echo '<th>DURATION</th>';
            echo '<th></th>';
        echo '</tr>';
        foreach($reportParser->getEncounters() as $key => $encounter) {
            $duration        = StringFormatter::getDurationFromStartAndEndTime($encounter['start_time'], $encounter['end_time']);
            $httpQuery       = HttpQueryManager::getEncounterHttpQuery(['reportId' => $params['reportId'], 'fightId' => $encounter['id']]);
            $classKillStatus = isset($encounter['kill']) && $encounter['kill'] ? 'cleared' : null;

            echo '<tr class="'.$classKillStatus.'">';
                echo '<td>'.$encounter['id'].'</td>';
                echo '<td>'.$encounter['name'].'</td>';
                echo '<td>'.$duration.'</td>';
                echo '<td>';
                    echo '<a class="fwb" href="' . $httpQuery . '">⋙</a>';
                echo '</td>';
            echo '</tr>';
        }
    echo '</table>';
} else {
    echo '<div class="m5 fc-error fwb">';
        if($reportParser->hasError()){
            echo $reportParser->getErrorMessage();
        }else{
            echo $formValidator->getErrorMessage();
        }
    echo '</div>';
    require_once('views/_reportIdForm.php');
}
