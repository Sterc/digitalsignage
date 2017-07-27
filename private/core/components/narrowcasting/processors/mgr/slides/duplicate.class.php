<?php

    /**
     * Narrowcasting
     *
     * Copyright 2016 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
     *
     * Narrowcasting is free software; you can redistribute it and/or modify it under
     * the terms of the GNU General Public License as published by the Free Software
     * Foundation; either version 2 of the License, or (at your option) any later
     * version.
     *
     * Narrowcasting is distributed in the hope that it will be useful, but WITHOUT ANY
     * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
     * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
     *
     * You should have received a copy of the GNU General Public License along with
     * Narrowcasting; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
     * Suite 330, Boston, MA 02111-1307 USA
     */

    class NarrowcastingSlidesDuplicateProcessor extends modProcessor {
        /**
         * @access public.
         * @var String.
         */
        public $classKey = 'NarrowcastingSlides';

        /**
         * @access public.
         * @var Array.
         */
        public $languageTopics = array('narrowcasting:default');

        /**
         * @access public.
         * @var String.
         */
        public $objectType = 'narrowcasting.slides';

        /**
         * @access public.
         * @var Object.
         */
        public $narrowcasting;

        /**
         * @access public.
         * @return Mixed.
         */
        public function initialize() {
            $this->narrowcasting = $this->modx->getService('narrowcasting', 'Narrowcasting', $this->modx->getOption('narrowcasting.core_path', null, $this->modx->getOption('core_path').'components/narrowcasting/').'model/narrowcasting/');

            if (null === $this->getProperty('published')) {
                $this->setProperty('published', 0);
            }

            return parent::initialize();
        }

        /**
         * @access public
         * @return Mixed.
         */
        public function process() {
            $criterea = array(
                'id' => $this->getProperty('id')
            );

            if (null !== ($original = $this->modx->getObject($this->classKey, $criterea))) {
                if (null !== ($duplicate = $this->modx->newObject($this->classKey))) {
                    $duplicate->fromArray(array_merge($original->toArray(), array(
                        'name' => $this->getProperty('name')
                    )));

                    if ($duplicate->save()) {
                        return $this->success('', array(
                            'id'=> $duplicate->id
                        ));
                    }
                }
            }

            return $this->failure();
        }
    }

    return 'NarrowcastingSlidesDuplicateProcessor';

?>