<?php

/**
 * Digital Signage
 *
 * Copyright 2019 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
 */

class DigitalSignageCheckboxgroupGetListProcessor extends modObjectGetListProcessor
{
    /**
     * Get the data of the query
     * @return array
     */
    public function getData() {
        $data = array();
        $data['results'] = json_decode(file_get_contents($this->getProperty('url')), true);
        return $data;
    }

    /**
     * Prepare the row for iteration
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(array $object) {
        return $object;
    }
}

return 'DigitalSignageCheckboxgroupGetListProcessor';
