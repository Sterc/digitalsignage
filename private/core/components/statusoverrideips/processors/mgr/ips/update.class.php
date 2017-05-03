<?php
class IPUpdateProcessor extends modObjectUpdateProcessor {
    public $classKey = 'soIP';
    public $languageTopics = array('statusoverrideips:default');
    public $objectType = 'statusoverrideips.ip';
}
return 'IPUpdateProcessor';