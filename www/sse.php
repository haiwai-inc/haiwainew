<?php
include "../../inc.comm.php";


use Hhxsv5\SSE\Event;
use Hhxsv5\SSE\SSE;
use Hhxsv5\SSE\StopSSEException;

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');
header('X-Accel-Buffering: no');

$callback = function () {
    $id = mt_rand(1, 1000);
    $news = [['id' => $id, 'title' => 'title ' . $id, 'content' => 'content ' . $id]]; // Get news from database or service.
    if (empty($news)) {
        return false; // Return false if no new messages
    }
    $shouldStop = false; // Stop if something happens or to clear connection, browser will retry
    if ($shouldStop) {
        throw new StopSSEException();
    }
    return json_encode(compact('news'));
    // return ['event' => 'ping', 'data' => 'ping data']; // Custom event temporarily: send ping event
    // return ['id' => uniqid(), 'data' => json_encode(compact('news'))]; // Custom event Id
};
(new SSE(new Event($callback, 'news')))->start();

//echo 1;

/*
phpinfo();
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('Connection: keep-alive');
header("Connection: ");
// phpinfo();
// while(true){
    $time = date('r');
    echo "data:Time: $time\n\n";
    @ob_flush();flush();
    sleep(1);
    // }
*/
