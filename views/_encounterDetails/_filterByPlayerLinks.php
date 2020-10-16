<?php
    foreach($filterByPlayersUrl as $player){
        echo '<a href="'.$player['url'].'">'.$player['name'].'</a>&emsp;';
    }
