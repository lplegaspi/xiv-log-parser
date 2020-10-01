<?php
class ReportRepository
{
    public static function findReportById($id)
    {
        $query = http_build_query(['api_key' => FfLogsApiConstants::KEY_PRIVATE]);
        $url   = FfLogsApiConstants::URL_BASE . FfLogsApiConstants::URL_REPORT_SEARCH . $id . '?' . $query;

        return (new CurlManager)->get($url);
    }

    public static function findEncounterEvents($params)
    {
        // get damage taken
        $damageTaken       = self::findDamageTakenByParams($params);
        $nextPageTimestamp = isset($damageTaken['nextPageTimestamp']) ? $damageTaken['nextPageTimestamp'] : $params['endTime'];

        // get healing events until nextPageTimestamp
        $healing           = self::findHealingByParams($params, $nextPageTimestamp);

        // merge damage taken and healing events
        $events            = array_merge($damageTaken['events'], $healing);
        $timestamps        = array_column($events, 'timestamp');

        // sort merged events by timestamp
        array_multisort ($timestamps, SORT_ASC, $events);

        return ['events' => $events, 'nextPageTimestamp' => $nextPageTimestamp];
    }

    public static function findDamageTakenByParams($params)
    {
        $query = http_build_query([
            'start'   => isset($params['pageStartTime']) ? $params['pageStartTime'] : $params['startTime'],
            'end'     => $params['endTime'],
            'api_key' => FfLogsApiConstants::KEY_PRIVATE
        ]);
        $url  = FfLogsApiConstants::URL_BASE . FfLogsApiConstants::URL_ENCOUNTER_DAMAGE_TAKEN . $_GET['reportId'] . '?' . $query;

        return (new CurlManager)->get($url);
    }

    public static function findHealingByParams($params, $maxPageTimestamp)
    {
        $hasReachedTimestampLimit = false;
        $currentPageTimestamp     = isset($params['pageStartTime']) ? $params['pageStartTime'] : $params['startTime'];
        $data                     = [];
        $count = 1;

        while($hasReachedTimestampLimit == false){
            $query = http_build_query([
                'start'   => $currentPageTimestamp,
                'end'     => $params['endTime'],
                'api_key' => FfLogsApiConstants::KEY_PRIVATE
            ]);

            $url = FfLogsApiConstants::URL_BASE . FfLogsApiConstants::URL_ENCOUNTER_HEALING . $_GET['reportId'] . '?' . $query;
            $res = (new CurlManager)->get($url);

            $filtered = array_filter($res['events'], function($value) use($maxPageTimestamp){
                return $value['timestamp'] < $maxPageTimestamp;
            });

            $currentPageTimestamp     = isset($res['nextPageTimestamp']) ? $res['nextPageTimestamp'] : null;
            $hasReachedTimestampLimit = count($filtered) < count($res['events']) || is_null($currentPageTimestamp);

            $data                     = array_merge($data, $res['events']);
        }

        return $data;
    }

    


    public static function findAllEncounterEvents($params)
    {
        //findAllEncounterDamageTaken
        $damageTaken = self::findAllEncounterDamageTaken($params);
        //findAllEncounterHealing
        $healing = self::findAllEncounterHealing($params);

        // merge and sort by timestamp
        $merged     = array_merge($damageTaken, $healing);
        $timestamps = array_column($merged, 'timestamp');

        array_multisort($timestamps, SORT_ASC, $merged);

        // return merged and sorted;
        return $merged;
    }

    public static function findAllEncounterDamageTaken($params)
    {
        $res        = [];
        $isLastPage = false;
        $reportId   = $params['reportId'];
        $startTime  = $params['startTime'];
        $endTime    = $params['endTime'];

        while(is_null($startTime) == false){
            // Setup cURL
            $query = http_build_query([
                'start'   => $startTime,
                'end'     => $endTime,
                'api_key' => FfLogsApiConstants::KEY_PRIVATE
            ]);
            $url  = FfLogsApiConstants::URL_BASE . FfLogsApiConstants::URL_ENCOUNTER_DAMAGE_TAKEN . $reportId . '?' . $query;

            // Execute cURL
            $raw = (new CurlManager)->get($url);
            $res = array_merge($res, $raw['events']);

            // Set next page params
            $startTime = isset($raw['nextPageTimestamp']) && $raw['nextPageTimestamp'] != $startTime
                ? $raw['nextPageTimestamp'] 
                : null
            ;
        }

        return $res;
    }




    public static function findAllEncounterHealing($params)
    {
        $res       = [];
        $isLastPage = false;
        $reportId  = $params['reportId'];
        $startTime = $params['startTime'];
        $endTime   = $params['endTime'];

        while(is_null($startTime) == false){
            // Setup cURL
            $query = http_build_query([
                'start'   => $startTime,
                'end'     => $endTime,
                'api_key' => FfLogsApiConstants::KEY_PRIVATE
            ]);
            $url  = FfLogsApiConstants::URL_BASE . FfLogsApiConstants::URL_ENCOUNTER_HEALING . $reportId . '?' . $query;
            
            // Execute cURL
            $raw = (new CurlManager)->get($url);
            $res = array_merge($res, $raw['events']);

            // Set next page params
            $startTime = isset($raw['nextPageTimestamp']) && $raw['nextPageTimestamp'] != $startTime
                ? $raw['nextPageTimestamp'] 
                : null
            ;
        }

        return $res;
    }

    public static function findAllFightDamageTakenByParams($params = []){
        $res        = [];
        $isLastPage = false;
        $nextPage   = null;
        $reportId   = $params['reportId'];
        $startTime  = $params['startTime'];
        $endTime    = $params['endTime'];
        $targetId   = isset($params['targetId']) ? $params['targetId'] : null;

        while($isLastPage !== true){
            $ch        = curl_init();
            $getParams = http_build_query([
                'start'    => $nextPage ?: $params['startTime'],
                'end'      => $params['endTime'],
                'sourceid' => isset($params['targetId']) ? $params['targetId'] : null,
            ]);
            $url = FfLogsApiConstants::URL_BASE
                . FfLogsApiConstants::URL_FIGHT_SEARCH
                . $params['reportId']
                . '?' . $getParams
                . '&api_key=' . FfLogsApiConstants::KEY_PRIVATE
            ;
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
echo $url;exit;
            $pageRes  = json_decode(curl_exec($ch), true);

            if(isset($pageRes['nextPageTimestamp'])){
                $nextPage = $pageRes['nextPageTimestamp'];
            } else {
                $isLastPage = true;
            }

            $res = array_merge($res, $pageRes['events']);

            curl_close($ch);
        }

        // https://www.fflogs.com/v1/report/events/healing/NwYb8xZnC1X6Jrtk
        // ?start=792608&end=1380855&api_key=f350cef3ffbe5ec09f91763c4b77dbb8

        $res  = [];
        
        while($isLastPage !== true){
            $query = http_build_query([
                'start' => $params['startTime'],
                'end' => $params['endTime'],
                'api_key' => FfLogsApiConstants::KEY_PRIVATE
            ]);
            $url  = FfLogsApiConstants::URL_BASE . FfLogsApiConstants::URL_ENCOUNTER_DAMAGE_TAKEN . $params['reportId'] . '?' . $query;


        }


        return (new CurlManager)->get($url);


        return $res;







    }
}
