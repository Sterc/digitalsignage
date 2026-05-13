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
     * Iterate across the data
     *
     * @param array $data
     * @return array
     */
    public function iterate(array $data) {
        $list = array();
        $list = $this->beforeIteration($list);
        $this->currentIndex = 0;
        /** @var xPDOObject|modAccessibleObject $object */
        foreach ($data['results'] as $object) {
            if ($this->checkListPermission && $object instanceof modAccessibleObject && !$object->checkPolicy('list')) continue;
            //$objectArray = $this->prepareRow($object);
            $objectArray = $object;
            if (!empty($objectArray) && is_array($objectArray)) {
                $list[] = $objectArray;
                $this->currentIndex++;
            }
        }
        $list = $this->afterIteration($list);
        return $list;
    }
}

return 'DigitalSignageCheckboxgroupGetListProcessor';
