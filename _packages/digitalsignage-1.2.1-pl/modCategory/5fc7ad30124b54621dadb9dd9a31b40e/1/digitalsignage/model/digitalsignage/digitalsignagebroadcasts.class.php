<?php

/**
 * Digital Signage
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

class DigitalSignageBroadcasts extends xPDOSimpleObject {
    /**
     * @access public.
     * @var Object|Null.
     */
    public $resource = null;

    /**
     * @access public.
     * @return Object|Null.
     */
    public function getResource()
    {
        if ($this->resource === null) {
            $resource = $this->getOne('modResource');

            if ($resource) {
                $this->resource = $resource;
            }
        }

        return $this->resource;
    }

    /**
     * @access public.
     * @return Boolean.
     */
    public function hasResource()
    {
        return $this->getResource() !== null;
    }

    /**
     * @access public.
     * @param Null|Integer $player.
     * @return Array|Boolean.
     */
    public function isScheduled($player = null) {
        $dates  = $this->getSchedules('date', $player);
        $days   = $this->getSchedules('day', $player);
        $date   = [
            'date'  => date('Y-m-d'),
            'time'  => date('H:i:s')
        ];

        foreach ($dates as $schedule) {
            if ($schedule->isScheduledFor($date, $date)) {
                return $schedule;
            }
        }

        foreach ($days as $schedule) {
            if ($schedule->isScheduledFor($date, $date)) {
                return $schedule;
            }
        }

        return false;
    }

    /**
     * @access public.
     * @param Null|String $type.
     * @param Null|Integer $player.
     * @return Array.
     */
    public function getSchedules($type = null, $player = null)
    {
        $schedules = [];

        if (null === $type || in_array($type, ['day', 'date'], true)) {
            foreach ($this->getMany('getSchedules') as $schedule) {
                if (
                    ($type !== null || $type === $schedule->get('type')) &&
                    ($player === null || (int) $player === (int) $schedule->get('player_id'))
                ) {
                    $schedules[] = $schedule;
                }
            }
        }

        return $schedules;
    }

    /**
     * @access public.
     * @return Array.
     */
    public function getSlides()
    {
        $slides = [];

        foreach ($this->getMany('getSlides') as $slide) {
            $index = $slide->get('sortindex');
            $slide = $slide->getOne('getSlide');

            if ($slide) {
                if ($slide->getOne('getSlideType')) {
                    if ((int) $slide->get('published') === 1) {
                        $slides[$index] = $slide;
                    }
                }
            }
        }

        ksort($slides);

        return $slides;
    }

    /**
     * @access public.
     * @param String $type.
     * @return Array.
     */
    public function getFeeds($type = 'all')
    {
        $feeds = [];

        foreach ($this->getMany('getFeeds') as $feed) {
            if ((int) $feed->get('published') === 1) {
                if ($type === 'content') {
                    if ((int) $feed->get('frequency') === 0) {
                        $feeds[] = $feed;
                    }
                } else if ($type === 'specials') {
                    if ((int) $feed->get('frequency') >= 1) {
                        $feeds[] = $feed;
                    }
                } else {
                    $feeds[] = $feed;
                }
            }
        }

        return $feeds;
    }

    /**
     * @access public.
     * @return Array.
     */
    public function getPlayers()
    {
        $players = [];

        foreach ($this->getMany('getSchedules') as $schedule) {
            $player = $schedule->getOne('getPlayer');

            if ($player) {
                if (!isset($players[(int) $player->get('id')])) {
                    $players[(int) $player->get('id')] = $player;
                }
            }
        }

        return $players;
    }

    /**
     * @access public.
     * @return Array.
     */
    public function getTickerItems()
    {
        require_once __DIR__ . '/digitalsignagereadfeeds.class.php';

        $readFeeds = new DigitalSignageReadFeeds($this->xpdo);

        return (array) $readFeeds->readDigitalSignageFeeds([
            'type'                  => 'array',
            'makeImagesRequired'    => false,
            'url'                   => $this->get('ticker_url')
        ]);
    }

    /**
     * @access public.
     * @return Boolean.
     */
    public function sync()
    {
        $slides = [];

        foreach ((array) $this->getSlides() as $key => $slide) {
            $data = (array) unserialize($slide->get('data'));

            $slides[] = array_merge([
                'id'        => $slide->get('id'),
                'time'      => $slide->get('time'),
                'slide'     => $slide->getOne('getSlideType')->get('key'),
                'source'    => 'intern',
                'title'     => $slide->get('name'),
                'image'     => null
            ], $data);
        }

        return $this->toExport($slides);
    }

    /**
     * @access public.
     * @return Boolean.
     */
    public function needSync()
    {
        $export = $this->getLastSync();

        if ($export) {
            $timestamp = strtotime($export);

            if (strtotime($this->get('editedon')) <= $timestamp) {
                foreach ($this->getSlides() as $slide) {
                    if (strtotime($slide->get('editedon')) >= $timestamp) {
                        return true;
                    }
                }

                return false;
            }
        }

        return true;
    }

    /**
     * @access public.
     * @return String|Boolean.
     */
    public function getLastSync()
    {
        $export = $this->getExportFile();

        if ($export && file_exists($export)) {
            return date('Y-m-d H:i:s', filemtime($export));
        }

        return false;
    }

    /**
     * @access public.
     * @param Array $slides.
     * @return Boolean.
     */
    public function toExport(array $slides = []) {
        $export = $this->getExportFile();

        if ($export) {
            $handle = fopen($export, 'wb');

            if ($handle) {
                fwrite($handle, json_encode([
                    'slides' => $slides
                ]));

                fclose($handle);

                return true;
            }
        }

        return false;
    }

    /**
     * @access public.
     * @return Array.
     */
    public function fromExport()
    {
        $output = [];
        $export = $this->getExportFile();

        if ($export && file_exists($export)) {
            $handle = fopen($this->getExportFile(), 'rb');

            if ($handle) {
                $slides = fread($handle, filesize($export));
                $data   = (array) json_decode($slides, true);

                if (isset($data['slides'])) {
                    $output = $data['slides'];
                }

                fclose($handle);
            }
        }

        return $output;
    }

    /**
     * @access public.
     * @return String|Boolean.
     */
    public function getExportFile() {
        $path = $this->getExportPath();

        if ($path) {
            return $path . 'broadcast-' . $this->get('id') . '.export';
        }

        return false;
    }

    /**
     * @access public.
     * @return String|Boolean.
     */
    public function getExportPath()
    {
        $path = dirname(dirname(__DIR__)) . '/export/';

        if (!is_dir($path) || !is_writable($path)) {
            return false;
        }

        return $path;
    }
}
