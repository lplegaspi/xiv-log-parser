<?php
class FfLogsApiConstants {
    const KEY_PRIVATE                = 'f350cef3ffbe5ec09f91763c4b77dbb8';
    const KEY_PUBLIC                 = '42cef46916dc948addaea3c59c321518';

    const REPORT_ID_LENGTH           = 16;
    
    const URL_BASE                   = 'https://www.fflogs.com:443/v1';
    const URL_REPORT_SEARCH          = '/report/fights/';
    const URL_ENCOUNTER_DAMAGE_TAKEN = '/report/events/damage-taken/';
    const URL_ENCOUNTER_HEALING      = '/report/events/healing/';

    const EVENT_TYPE_DAMAGE_TAKEN    = 'damage-taken';
    const EVENT_TYPE                 = 'healing';
}
