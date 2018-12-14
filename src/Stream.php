<?php

namespace Mintopia\ReutersLive;

class Stream
{
    public $title;
    public $guid;
    public $description;
    public $location;
    public $stream;
    public $uri;

    public static function createFromData($data, $stream, $uri)
    {
        $obj = new Stream;
        $obj->title = $data->title;
        $obj->guid = $data->guid;
        $obj->description = $data->description;
        $obj->location = $data->location;
        $obj->stream = $stream;
        $obj->uri = $uri;
        return $obj;
    }
}