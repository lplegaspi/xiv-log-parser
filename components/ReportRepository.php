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
        $url  = FfLogsApiConstants::URL_BASE . FfLogsApiConstants::URL_ENCOUNTER_DAMAGE_TAKEN . $params['reportId'] . '?' . $query;

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

            $url = FfLogsApiConstants::URL_BASE . FfLogsApiConstants::URL_ENCOUNTER_HEALING . $params['reportId'] . '?' . $query;
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
}
