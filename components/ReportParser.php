<?php
class ReportParser
{
    private $report;
    private $encounterEvents;

    public function __construct($report)
    {
        $this->report = $report;
    }

    public function setEncounterEvents($events)
    {
        $this->encounterEvents = $events;
    }

    public function isValidReport()
    {
        return isset($this->report['fights']);
    }

    public function hasError()
    {
        return isset($this->report['status'], $this->report['error']);
    }

    public function hasEncounters()
    {
        return isset($this->report['fights']) && count($this->report['fights']);
    }

    public function getReport()
    {
        return $this->report;
    }

    public function getEncounters()
    {
        return $this->report['fights'];
    }

    public function getEncounterEvents()
    {
        return isset($this->encounterEvents['events']) ? $this->encounterEvents['events'] : [];
    }

    public function getErrorMessage()
    {
        return $this->report['error'];
    }

    public function getStartAndEndTimeByFightId($fightId)
    {
        foreach($this->getEncounters() as $encounter){
            if($encounter['id'] == $fightId){
                return [
                    'startTime' => $encounter['start_time'],
                    'endTime'   => $encounter['end_time'],
                ];
            }
        }

        return null;
    }

    public function getParticipants($type = null)
    {
        switch($type){
            case 'players': 
                if(isset($this->report['friendlies'])){
                    $friendlies = [];
                    foreach($this->report['friendlies'] as $friendly){
                        if(isset($friendly['server'])){
                            array_push($friendlies, $friendly);
                        }
                    }
                    return $friendlies;
                }

                return [];
            break;
        }

        $friendlies   = isset($this->report['friendlies'])   ? $this->report['friendlies']   : [];
        $enemies      = isset($this->report['enemies'])      ? $this->report['enemies']      : [];
        $friendlyPets = isset($this->report['friendlyPets']) ? $this->report['friendlyPets'] : [];
        $enemyPets    = isset($this->report['enemyPets'])    ? $this->report['enemyPets']    : [];
        
        $participants = array_merge($friendlies, $enemies, $friendlyPets, $enemyPets);

        // return participant list with their IDs as keys
        return array_combine(array_column($participants, 'id'), $participants);
    }

    public function getFilterByPlayerUrls($params)
    {
        $players = $this->getParticipants('players');
        $urls    = [];

        foreach($players as $player){
            if(in_array($params['fightId'], array_column($player['fights'],'id'))){
                $urls[$player['id']] = [
                    'name' => $player['name'],
                    'url'  => HttpQueryManager::getEncounterHttpQuery(array_merge($params, ['targetId' => $player['id']])),
                ];
            }
        }

        return $urls;
    }

    public function getPageLink($type, $params)
    {
        switch($type){
            case 'first':
                if(!$this->isOnFirstPage($params)){
                    return HttpQueryManager::getEncounterHttpQuery([
                        'reportId'      => $params['reportId'],
                        'fightId'       => $params['fightId'],
                        'targetId'      => $params['targetId'],
                    ]);
                }
            break;
            case 'next':
                if(!$this->isOnLastPage($params)){
                    return HttpQueryManager::getEncounterHttpQuery([
                        'reportId'      => $params['reportId'],
                        'fightId'       => $params['fightId'],
                        'targetId'      => $params['targetId'],
                        'pageStartTime' => $this->getNextPageTimestamp(),
                    ]);
                }
            break;
        }

        return null;
    }

    public function isOnFirstPage($params)
    {
        return !isset($params['pageStartTime']);
    }

    public function isOnLastPage($params)
    {
        $nextPageTimestamp = $this->getNextPageTimestamp();
        return !$nextPageTimestamp || $params['endTime'] == $nextPageTimestamp;
    }

    public function getNextPageTimestamp()
    {
        return isset($this->encounterEvents['nextPageTimestamp'])
            ? $this->encounterEvents['nextPageTimestamp']
            : null
        ;
    }

    public static function getSourceNameOfEvent($event, $participants)
    {
        if(isset($event['source'], $event['source']['name'])){
            return $event['source']['name'];
        }elseif(isset($event['sourceID'], $participants[$event['sourceID']])){
            return $participants[$event['sourceID']]['name'];
        }

        return 'N/A';
    }
}
