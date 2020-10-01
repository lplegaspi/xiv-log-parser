<?php
class StringFormatter
{
    public static function getDurationFromStartAndEndTime($startTime, $endTime){
        $seconds  = ($endTime - $startTime) / 1000;
        $mins     = floor($seconds / 60 % 60);
        $secs     = floor($seconds % 60);

        return sprintf('%02d:%02d', $mins, $secs);
    }

    public static function getEncounterHttpQueryByParams($reportId, $startTime, $endTime, $pageStartTime = null){
        if($pageStartTime >= $endTime) {
            return null;
        }
        
        $params = [
            'reportId'  => $reportId,
            'startTime' => $startTime,
            'endTime'   => $endTime,
            'api_key'   => FfLogsApiConstants::KEY_PRIVATE
        ];

        if($pageStartTime){
            $params['pageStartTime'] = $pageStartTime;
        }

        return '?' . http_build_query($params);
    }
}
