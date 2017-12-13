<?php

	class DigitalSignageSlides extends xPDOSimpleObject {
	    /**
         * @access public.
         * @return Array.
         */
	    public function getBroadcasts() {
            $broadcasts = array();

            foreach ($this->getMany('getBroadcasts') as $broadcast) {
                if (null !== ($broadcast = $broadcast->getOne('getBroadcast'))) {
                    $broadcasts[] = $broadcast;
                }
            }
            return $broadcasts;
        }
    }
	
?>