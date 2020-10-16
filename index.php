<html>
    <head>
        <link rel="stylesheet" href="assets/css/style.css">
    </head>
    <body>
        <?php
            require_once('_autoloader.php');

            $formValidator = new FormValidator(HttpQueryManager::parseGetParams());
            $params        = $formValidator->getParams();

            if($formValidator->hasReportIdParam() && $formValidator->isValidReportId()){
                $reportId     = StringFormatter::getParsedReportId($params['reportId']);
                $reportParser = new ReportParser(ReportRepository::findReportById($reportId));

                if($reportParser->isValidReport() && $formValidator->hasSpecificEncounterParams()){
                    $participants       = $reportParser->getParticipants();
                    $filterByPlayersUrl = $reportParser->getFilterByPlayerUrls($params);
                    $startEndTime       = $reportParser->getStartAndEndTimeByFightId($params['fightId']);

                    $reportParser->setEncounterEvents(ReportRepository::findEncounterEvents(array_merge($params, $startEndTime)));
                    
                    $firstPageLink      = $reportParser->getPageLink('first', array_merge($params, $startEndTime));
                    $nextPageLink       = $reportParser->getPageLink('next', array_merge($params, $startEndTime));
                    $events             = $reportParser->getEncounterEvents();

                    if($reportParser->hasEncounterEntries()){
                        require_once('views/_encounterDetails.php');
                    } else {
                        require_once('views/_noEncounterEntries.php');
                    }
                } else {
                    require_once('views/_encounterList.php');
                }
            } else {
                require_once('views/_indexFormContainer.php');
            }
        ?>
    </body>
</html>

