<?php
function __autoload ($className)
{
    $dir = [
        'components/',
        'components/helpers/',
        'constants/',
        'views/'
    ];

    foreach($dir as $dirName){
        $path = $dirName . $className . '.php';
        if (file_exists($path)) {
            require_once $path;
        }
    }
}
