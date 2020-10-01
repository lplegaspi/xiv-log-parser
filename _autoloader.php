<?php
define ("CLASS_DIRECTORIES", ['components/', 'components/helpers/', 'constants/', 'views/']);
function __autoload ($className)
{
    foreach(CLASS_DIRECTORIES as $directory){
        $path = $directory . $className . '.php';
        if (file_exists($path)) {
            require_once $path;
        }
    }
}
