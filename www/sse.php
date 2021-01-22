<?php
include "./inc.comm.php";
phpinfo();
// header('Content-Type: text/event-stream');
// header('Cache-Control: no-cache');
// header('Connection: keep-alive');
// header("Connection: ");
// phpinfo();
// while(true){
    $time = date('r');
    echo "data:Time: $time\n\n";
    @ob_flush();flush();
    sleep(1);
    // }