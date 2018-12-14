<?php
namespace Mintopia\ReutersLive;

use GuzzleHttp\Client;

class ReutersLive
{
    protected $uri;

    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    public function getStreams($filter = null)
    {
        $html = $this->getHtml();
        $json = $this->getJson($html);
        $streams = $this->parseJson($json);
        if (!$filter) {
            return $streams;
        }
        return array_filter($streams, function($stream) use ($filter) {
            return $stream->status == $filter;
        });
    }

    public function getLiveStreams()
    {
        return $this->getStreams(Stream::STATUS_LIVE);
    }

    public function getUpcomingStreams()
    {
        return $this->getStreams(Stream::STATUS_UPCOMING);
    }

    protected function getHtml()
    {
        $client = new Client([
            'headers' => [
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.77 Safari/537.36'
            ]
        ]);
        try {
            $response = $client->get($this->uri);
            return (string)$response->getBody();
        } catch (\Exception $e) {
            throw new ReutersLiveException('Unable to download stream index: ' . $e->getMessage());
        }
    }

    protected function getJson($html)
    {
        $matches = [];
        preg_match('/var RTVJson = (?<json>.*);\s*\<\/script\>/m', $html, $matches);
        if (!isset($matches['json'])) {
            throw new ReutersLiveException('Unable to find JSON');
        }

        $obj = json_decode($matches['json']);
        if ($obj === null) {
            throw new ReutersLiveException('Unable to parse JSON: ' . json_last_error_msg());
        }
        return $obj;
    }

    protected function parseJson($json)
    {
        $streams = [];
        foreach ($json->items as $item) {
            if ($item->type != Stream::STATUS_LIVE && $item->type != Stream::STATUS_UPCOMING) {
                continue;
            }
            $stream = null;
            $uri = null;
            foreach ($item->resources as $resource) {
                if ($resource->entityType == 'UriResource' && $resource->uriType == 'seo') {
                    $uri = $resource->uri;
                }
                if ($resource->entityType == 'VideoStreamResource') {
                    $stream = $resource->uri;
                }
            }

            if (!$stream) {
                $stream = 'https://churro.prod.reuters.tv/rest/v2/live/playlist/story/' . $item->id;
            }

            $streams[] = Stream::createFromData($item, $stream, $uri);;
        }
        return $streams;
    }
}