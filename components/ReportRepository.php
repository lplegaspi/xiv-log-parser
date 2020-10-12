<?php
class ReportRepository
{
    public static function findReportById($id)
    {
        $query = '?' . http_build_query(['api_key' => FfLogsApiConstants::KEY]);
        $url   = FfLogsApiConstants::URL_BASE . FfLogsApiConstants::URL_REPORT_SEARCH . $id . $query ;

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
        $url = self::buildUrl('damage-taken', $params);
        return (new CurlManager)->get($url);
    }

    public static function findHealingByParams($params, $maxPageTimestamp)
    {
        $hasReachedTimestampLimit = false;
        $currentPageTimestamp     = isset($params['pageStartTime']) ? $params['pageStartTime'] : $params['startTime'];
        $data                     = [];

        while($hasReachedTimestampLimit == false){
            $url = self::buildUrl('healing', $params);
            $res = (new CurlManager)->get($url);

            $filtered = array_filter($res['events'], function($value) use($maxPageTimestamp){
                return $value['timestamp'] < $maxPageTimestamp;
            });

            $params['pageStartTime']  = isset($res['nextPageTimestamp']) ? $res['nextPageTimestamp'] : null;
            $hasReachedTimestampLimit = count($filtered) < count($res['events']) || is_null($params['pageStartTime']);
            $data                     = array_merge($data, $res['events']);
        }

        return $data;
    }

    public static function buildUrl($type, $params)
    {
        $urlBase = FfLogsApiConstants::URL_BASE;

        switch($type){
            case 'damage-taken': 
                $urlBase        .= FfLogsApiConstants::URL_ENCOUNTER_DAMAGE_TAKEN;
                $targetParamKey = 'sourceid';
            break;
            case 'healing':
                $urlBase        .= FfLogsApiConstants::URL_ENCOUNTER_HEALING;
                $targetParamKey = 'targetid';
            break;
        }

        $urlBase     .= StringFormatter::getParsedReportId($params['reportId']) . '?';

        $paramsBase = [
            'start'   => isset($params['pageStartTime']) ? $params['pageStartTime'] : $params['startTime'],
            'end'     => $params['endTime'],
            'api_key' => FfLogsApiConstants::KEY
        ];

        if(isset($params['targetId'])){
            $paramsBase[$targetParamKey] = $params['targetId'];
        }

        $query = http_build_query($paramsBase);
        return $urlBase . $query;
    }
}
