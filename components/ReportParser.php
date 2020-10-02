<?php
class ReportParser
{
    public static function hasReportIdParam(){
        return isset($_GET['reportId']);
    }

    public static function hasChosenSpecificEncounter(){
        return isset($_GET['startTime']) && isset($_GET['endTime']);
    }

    public static function getPageLink($page, $params){

        switch($page){
            case 'first' :
                if(!self::isOnFirstPage($params)){
                    unset($params['pageStartTime']);
                    return HttpQueryManager::getEncounterHttpQuery($params);
                }
            break;
            case 'next'  : 
                if(!self::isOnLastPage($params)){
                    $nextPageParams = array_merge($params, ['pageStartTime' => $params['nextPageTimestamp']]);
                    unset($nextPageParams['nextPageTimestamp']);
                    return HttpQueryManager::getEncounterHttpQuery($nextPageParams);
                }
            break;
        }

        return null;
    }

    public static function isOnFirstPage($params){
        return !isset($params['pageStartTime']);
    }

    public static function isOnLastPage($params){
        return !isset($params['nextPageTimestamp']) || $params['endTime'] == $params['nextPageTimestamp'];
    }

    public static function getParticipantListFromEncounters($encounters){
        $participants = array_merge(
            $encounters['friendlies'],
            $encounters['enemies'],
            $encounters['friendlyPets'],
            $encounters['enemyPets']
        );

        return array_combine(array_column($participants, 'id'), $participants);
    }

    public static function getSourceNameOfEvent($event, $participants){
        if(isset($event['source'], $event['source']['name'])){
            return $event['source']['name'];
        }elseif(isset($event['sourceID'], $participants[$event['sourceID']])){
            return $participants[$event['sourceID']]['name'];
        }

        return 'N/A';
    }
}
