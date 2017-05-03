<?php
class IPGetListProcessor extends modObjectGetListProcessor {
    public $classKey = 'soIP';
    public $languageTopics = array('statusoverrideips:default');
    public $defaultSortField = 'id';
    public $defaultSortDirection = 'ASC';
    public $objectType = 'statusoverrideips.ip';
}
return 'IPGetListProcessor';