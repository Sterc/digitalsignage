<?php

/**
 * Digital Signage
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

class DigitalSignageSlides extends xPDOSimpleObject
{
    /**
     * @access public.
     * @return Array.
     */
    public function getBroadcasts()
    {
        $broadcasts = [];

        foreach ($this->getMany('getBroadcasts') as $broadcast) {
            $broadcast = $broadcast->getOne('getBroadcast');

            if ($broadcast) {
                $broadcasts[] = $broadcast;
            }
        }

        return $broadcasts;
    }
}
