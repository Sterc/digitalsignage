<?php

/**
 * Digital Signage
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

class DigitalSignagePlayers extends xPDOSimpleObject
{
    /**
     * @access public.
     * @return String.
     */
    public function getMode()
    {
        list($width, $height) = explode('x', $this->get('resolution'));

        return $width > $height ? 'landscape' : 'portrait';
    }

    /**
     * @access public.
     * @param String|Null $type.
     * @return Array.
     */
    public function getSchedules($type = null)
    {
        $schedules = [];

        if ($type === null || in_array($type, ['day', 'date'], true)) {
            foreach ($this->getMany('getSchedules') as $schedule) {
                if ($type === null || $type === $schedule->get('type')) {
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
    public function getBroadcasts()
    {
        $broadcasts = [];

        foreach ($this->getSchedules() as $schedule) {
            $broadcast = $schedule->getOne('getBroadcast');

            if ($broadcast) {
                if (!isset($broadcasts[(int) $broadcast->get('id')])) {
                    if ($broadcast->hasResource()) {
                        $broadcasts[(int) $broadcast->get('id')] = $broadcast;
                    }
                }
            }
        }

        return $broadcasts;
    }

    /**
     * @access public.
     * @return Object|Null.
     */
    public function getCurrentBroadcast()
    {
        if ($this->isOnline()) {
            $broadcast = $this->getOne('getCurrentBroadcast');

            if ($broadcast) {
                if ($broadcast->hasResource()) {
                    return $broadcast;
                }
            }
        }

        return null;
    }

    /**
     * @access public.
     * @param Integer $broadcast.
     * @return Boolean.
     */
    public function isOnline($broadcast = null)
    {
        $online = strtotime($this->get('last_online')) > time() - (5 * 60);

        if ($broadcast !== null) {
            $online = $online && (int) $broadcast === (int) $this->get('last_broadcast_id');
        }

        return $online;
    }

    /**
     * @access public.
     * @param Integer $time.
     * @param Integer $broadcast.
     * @return Boolean.
     */
    public function setOnline($time, $broadcast)
    {
        $restart = (int) $this->get('restart') === 1;

        if ($restart) {
            $this->set('restart', 0);
        }

        $this->fromArray([
            'last_online'       => date('Y-m-d H:i:s'),
            'last_online_time'  => $time,
            'last_broadcast_id' => $broadcast
        ]);

        $this->save();

        return $restart;
    }
}
