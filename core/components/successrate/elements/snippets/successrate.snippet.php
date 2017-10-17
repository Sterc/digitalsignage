<?php
/**
     * Success Rate
     *
     * Copyright 2017 by Oene Tjeerd de Bruin <oenetjeerd@sterc.nl>
     *
     * Success Rate is free software; you can redistribute it and/or modify it under
     * the terms of the GNU General Public License as published by the Free Software
     * Foundation; either version 2 of the License, or (at your option) any later
     * version.
     *
     * Success Rate is distributed in the hope that it will be useful, but WITHOUT ANY
     * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
     * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
     *
     * You should have received a copy of the GNU General Public License along with
     * Success Rate; if not, write to the Free Software Foundation, Inc., 59 Temple Place,
     * Suite 330, Boston, MA 02111-1307 USA
     */

    if ($modx->loadClass('SuccessRate', $modx->getOption('successrate.core_path', null, $modx->getOption('core_path').'components/successrate/').'model/successrate/', true, true)) {
        $successRate = new SuccessRate($modx);

        if ($successRate instanceof SuccessRate) {
            return $successRate->run($scriptProperties);
        }
    }