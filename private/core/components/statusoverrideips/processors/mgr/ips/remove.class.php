<?php
class IPRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'soIP';
    public $languageTopics = array('statusoverrideips:default');
    public $objectType = 'statusoverrideips.ip';
}
return 'IPRemoveProcessor';