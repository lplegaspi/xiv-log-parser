<?php
class HttpQueryManager
{
    public static function parseGetParams($params = [])
    {
        if(isset($_GET['reportId'])){
            $params['reportId'] = $_GET['reportId'];
        }

        if(isset($_GET['fightId'])){
            $params['fightId'] = $_GET['fightId'];
        }

        if(isset($_GET['startTime'], $_GET['endTime'])){
            $params['startTime'] = $_GET['startTime'];
            $params['endTime']   = $_GET['endTime'];
        }

        if(isset($_GET['pageStartTime'])){
            $params['pageStartTime'] = $_GET['pageStartTime'];
        }

        if(isset($_GET['targetId'])){
            $params['targetId'] = $_GET['targetId'];
        }

        return $params;
    }

    public static function makeReportUrl($reportId)
    {
        return '?' . http_build_query(['reportId' => $reportId]);
    }

    public static function getEncounterHttpQuery($params)
    {
        return '?' . http_build_query($params);
    }
}
