<?php
class HttpQueryManager
{
    public static function makeReportUrl($reportId){
        return '?' . http_build_query(['reportId' => $reportId]);
    }

    public static function getReportIdFromString($url){
        if(strlen($url) == FfLogsApiConstants::REPORT_ID_LENGTH){
            return $url;
        }

        $reportId = [];
        preg_match('/(?<=[reports]\/).*?(?=#)/', $url, $reportId);

        return isset($reportId[0]) ? $reportId[0] : null;
    }

    public static function getEncounterHttpQuery($params){
        return '?' . http_build_query($params);
    }
}
