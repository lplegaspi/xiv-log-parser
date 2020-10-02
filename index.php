<html>
    <head>
        <style>
            body, table {font-family:tahoma;font-size:12px;cursor:default;}
            .cleared {background-color:#cec;}
            table {border-collapse:collapse;}
            table.encounterEvents tbody tr:hover {background-color:#d2e1f1;}
            table.encounterEvents tbody tr {text-align:center;}
            table.encounterEvents tbody td.no-data {background-color:#ddd;}
            .fwb {font-weight:bold;}
        </style>
    </head>
    <body>
        <?php
            require_once('_autoloader.php');

            if(ReportParser::hasReportIdParam()){
                $reportId     = HttpQueryManager::getReportIdFromString($_GET['reportId']);
                $encounters   = ReportRepository::findReportById($reportId);
                $participants = ReportParser::getParticipantListFromEncounters($encounters);

                if(ReportParser::hasChosenSpecificEncounter()){
                    $getParams = [
                        'reportId'      => $reportId,
                        'startTime'     => $_GET['startTime'],
                        'endTime'       => $_GET['endTime'],
                        'pageStartTime' => isset($_GET['pageStartTime']) ? $_GET['pageStartTime'] : null
                    ];
                    $encounterEvents = ReportRepository::findEncounterEvents($getParams);
                    $nextPageLink    = ReportParser::getPageLink(
                        'next', array_merge($getParams, ['nextPageTimestamp' => $encounterEvents['nextPageTimestamp']])
                    );
                    $firstPageLink   = ReportParser::getPageLink('first', $getParams);

                    // load specific encounter
                    require_once('views/_encounterDetails.php');
                } else {
                    // load listof encounters in a report
                    require_once('views/_encounterList.php');
                }
            } else {
                // load form to input report ID
                require_once('views/_reportIdForm.php');
            }
        ?>
    </body>
</html>

