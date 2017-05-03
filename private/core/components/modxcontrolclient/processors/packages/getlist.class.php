<?php
/**
 * Gets a list of packages
 *
 * @param integer $workspace (optional) The workspace to filter by. Defaults to
 * 1.
 * @param integer $start (optional) The record to start at. Defaults to 0.
 * @param integer $limit (optional) The number of records to limit to. Defaults
 * to 10.
 * @param string $sort (optional) The column to sort by. Defaults to name.
 * @param string $dir (optional) The direction of the sort. Defaults to ASC.
 *
 * @var modX $this->modx
 * @var array $scriptProperties
 * @var modProcessor $this
 *
 * @package modx
 * @subpackage processors.workspace.packages
 */

require_once MODX_CORE_PATH . 'model/modx/processors/workspace/packages/getlist.class.php';

class mcModPackageGetListProcessor extends modPackageGetListProcessor {
    public function checkPermissions() { return true; }
}
return 'mcModPackageGetListProcessor';
