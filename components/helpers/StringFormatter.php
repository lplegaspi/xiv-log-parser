<?php
class StringFormatter
{
    public static function getDurationFromStartAndEndTime($startTime, $endTime)
    {
        $seconds  = ($endTime - $startTime) / 1000;
        $mins     = floor($seconds / 60 % 60);
        $secs     = floor($seconds % 60);

        return sprintf('%02d:%02d', $mins, $secs);
    }

    public static function getParsedReportId($reportId)
    {
        return preg_match(FfLogsApiConstants::REGEX_REPORT_ID, $reportId, $match) ? $match[1] : null;
    }
}
