<?php
class FormValidator
{
    private $params;
    private $errorMessage;

    public function __construct($params)
    {
        $this->params = [
            'reportId'      => isset($params['reportId'])      ? $params['reportId']      : null,
            'fightId'       => isset($params['fightId'])       ? $params['fightId']       : null,
            'pageStartTime' => isset($params['pageStartTime']) ? $params['pageStartTime'] : null,
            'targetId'      => isset($params['targetId'])      ? $params['targetId']      : null,
        ];
    }

    public function hasReportIdParam()
    {
        if(!is_null($this->params['reportId'])){
            if(strlen($this->params['reportId']) > 0){
                return true;
            }

            $this->setErrorMessage('Please specify a Report ID.');
            return false;
        }

        return false;
    }

    public function isValidReportId()
    {
        if(strlen($this->params['reportId']) > 0 && preg_match(FfLogsApiConstants::REGEX_REPORT_ID, $this->params['reportId'])){
            return true;
        }

        $this->errorMessage = 'Invalid Report ID specified.';
        return false;
    }

    public function hasSpecificEncounterParams()
    {
        return $this->isValidReportId() && $this->isValidFightId();
    }

    public function isValidFightId()
    {
        return strlen($this->params['fightId']) > 0 && $this->params['fightId'] > 0;
    }

    private function setErrorMessage($message)
    {
        $this->errorMessage = $message;
    }

    public function getErrorMessage()
    {
        return $this->errorMessage; 
    }

    public function getParams()
    {
        return $this->params;
    }
}
