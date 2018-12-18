<?php

namespace Mintopia\ReutersLive;

use Carbon\Carbon;

class Stream
{
    public $title;
    public $guid;
    public $description;
    public $location;
    public $stream;
    public $uri;
    public $status;
    public $start;
    public $end;

    public const STATUS_UPCOMING = 'LIVE_UPCOMING';
    public const STATUS_LIVE = 'LIVE_NOW';

    public static function createFromData($data, $stream, $uri)
    {
        $obj = new Stream;
        $obj->id = $data->id;
        $obj->title = $data->title;
        $obj->guid = $data->guid;
        if (property_exists($data, 'description')) {
          $obj->description = $data->description;
        }
        $obj->location = $data->location;
        $obj->stream = $stream;
        $obj->uri = $uri;
        $obj->status = $data->type;
        if (property_exists($data, 'date')) {
            if (property_exists($data->date, 'liveBroadcastStart')) {
                $obj->start = new Carbon($data->date->liveBroadcastStart);
            }
            if (property_exists($data->date, 'liveBroadcastEnd')) {
                $obj->end = new Carbon($data->date->liveBroadcastEnd);
            }
        }
        return $obj;
    }
}