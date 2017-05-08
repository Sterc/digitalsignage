<?php

require_once __DIR__ . '/../../model/narrowcasting/narrowcasting.class.php';

class NarrowcastingPlayerProcessor extends modProcessor
{

    private $narrowcasting = null;

    public function checkPermissions()
    {
        return $this->modx->hasPermission('file_manager');
    }

    public function process()
    {
        $this->narrowcasting = $this->modx->getService('narrowcasting', 'Narrowcasting', $this->modx->getOption('narrowcasting.core_path', null, $this->modx->getOption('core_path') . 'components/narrowcasting/') . 'model/narrowcasting/');

        $method = $this->getProperty('method');
        $data   = array();

        switch ($method) {
            case 'checkSchedule':
                $data = $this->checkSchedule();

                break;
        }

        return $this->outputArray($data);
    }

    private function checkSchedule()
    {
        return $this->narrowcasting->checkSchedule(
            $this->getProperty('player')
        );
    }

}

return 'NarrowcastingPlayerProcessor';
