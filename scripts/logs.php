<?php

$logFile = __DIR__.'/../storage/logs/laravel.log';
$lastpos = 0;

while (true) {
    if (! file_exists($logFile)) {
        continue;
    }

    usleep(300000);
    clearstatcache(false, $logFile);
    $len = filesize($logFile);

    if ($len < $lastpos) {
        $lastpos = $len;
    } elseif ($len > $lastpos) {
        $f = fopen($logFile, 'rb');

        if ($f === false) {
            exit();
        }

        fseek($f, $lastpos);

        while (! feof($f)) {
            $buffer = fread($f, 4096);
            echo $buffer;
            flush();
        }

        $lastpos = ftell($f);
        fclose($f);
    }
}
