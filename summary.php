<?php
require_once(__DIR__ . '/vendor/autoload.php');

$live = new \Mintopia\ReutersLive\ReutersLive();
$live->setUri('http://www.reuters.tv/live');
$liveStreams = $live->getLiveStreams();
foreach ($liveStreams as $stream) {
    echo str_pad('LIVE NOW', 18, ' ', STR_PAD_RIGHT);
    echo str_pad($stream->id, 10, ' ', STR_PAD_RIGHT);
    echo $stream->title;
    echo "\r\n";
}
$streams = $live->getUpcomingStreams();
foreach ($streams as $stream) {
    echo str_pad($stream->start->format('Y-m-d H:i'), 18, ' ', STR_PAD_RIGHT);
    echo str_pad($stream->id, 10, ' ', STR_PAD_RIGHT);
    echo $stream->title;
    echo "\r\n";
}