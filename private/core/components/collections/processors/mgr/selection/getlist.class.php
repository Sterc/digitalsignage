<?php

/**
 * Get list of Selections
 *
 * @package collections
 * @subpackage processors.selection
 */
class CollectionsSelectionGetListProcessor extends modObjectGetListProcessor
{
    public $classKey = 'modResource';
    public $defaultSortField = 'menuindex';
    public $defaultSortDirection = 'ASC';
    public $checkListPermission = true;
    public $languageTopics = array('resource', 'collections:default');

    public $tvColumns = array();
    public $taggerColumns = array();

    public $columnRenderer = array();

    public $actions = array();
    public $buttons = array();
    public $sortType = null;
    public $sortBefore = '';
    public $sortAfter = '';
    public $searchQueryExcludeTvs = false;
    public $searchQueryExcludeTagger = false;
    public $searchQueryTitleOnly = false;

    public function initialize()
    {
        $parent = $this->getProperty('parent', null);
        if (empty($parent)) {
            return false;
        }

        $this->setActions();

        $parentObject = $this->modx->getObject('modResource', $parent);
        /** @var CollectionTemplate $template */
        $template = $this->modx->collections->getCollectionsView($parentObject);

        $sort = $this->getProperty('sort');
        $sort = explode(':', $sort);

        if (isset($sort[1])) {
            $this->sortType = $sort[1];
            $this->setProperty('sort', $sort[0]);
        } else {
            $this->setProperty('sort', $sort[0]);

            /** @var CollectionTemplateColumn[] $columns */
            $columns = $template->getMany('Columns', array('name' => $sort[0]));
            if (count($columns) == 1) {
                foreach ($columns as $column) {
                    $this->sortType = $column->sort_type;
                }
            }
        }

        $this->sortBefore = $template->permanent_sort_before;
        $this->sortAfter = $template->permanent_sort_after;

        $this->searchQueryExcludeTvs = $template->search_query_exclude_tvs;
        $this->searchQueryExcludeTagger = $template->search_query_exclude_tagger;
        $this->searchQueryTitleOnly = $template->search_query_title_only;

        $buttons = $this->modx->collections->explodeAndClean($template->buttons, ',', 1);
        foreach ($buttons as $button) {
            $button = $this->modx->collections->explodeAndClean($button, ':');

            if (!isset($this->actions[$button[0]])) continue;

            if (isset($button[1])) {
                $this->actions[$button[0]]['className'] .= ' ' . $button[1];
            }

            $this->buttons[] = $button[0];
        }

        $templateColumnsQuery = $this->modx->newQuery('CollectionTemplateColumn');
        $templateColumnsQuery->where(array(
            'template' => $template->id,
        ));
        $templateColumnsQuery->where(array(
            'name:LIKE' => 'tv_%',
            'OR:name:LIKE' => 'tagger_%',
            'OR:php_renderer:!=' => '',
        ));
        $templateColumnsQuery->select($this->modx->getSelectColumns('CollectionTemplateColumn', '', '', array('name', 'php_renderer')));
        $templateColumnsQuery->prepare();
        $templateColumnsQuery->stmt->execute();

        while ($column = $templateColumnsQuery->stmt->fetch(PDO::FETCH_ASSOC)) {
            if (strpos($column['name'], 'tv_') !== false) {
                $tvName = preg_replace('/tv_/', '', $column['name'], 1);

                $tv = $this->modx->getObject('modTemplateVar', array('name' => $tvName));

                if ($tv) {
                    $this->tvColumns[] = array('id' => $tv->id, 'name' => $tvName, 'column' => $column['name'], 'default' => $tv->default_text);
                }
            }

            if (strpos($column['name'], 'tagger_') !== false) {
                $this->taggerColumns[] = $column['name'];
            }

            if ($column['php_renderer'] != '') {
                $snippet = $this->modx->getObject('modSnippet', array('name' => $column['php_renderer']));
                if ($snippet) {
                    $this->columnRenderer[$column['name']] = $column['php_renderer'];
                }
            }
        }

        return parent::initialize();
    }

    public function setActions()
    {
        $this->actions['view'] = array(
            'className' => 'view',
            'text' => $this->modx->lexicon('view'),
            'key' => 'view',
        );
        $this->actions['edit'] = array(
            'className' => 'edit',
            'text' => $this->modx->lexicon('edit'),
            'key' => 'edit',
        );
        $this->actions['quickupdate'] = array(
            'className' => 'quickupdate',
            'text' => $this->modx->lexicon('quick_update_resource'),
            'key' => 'quickupdate',
        );
        $this->actions['unpublish'] = array(
            'className' => 'unpublish',
            'text' => $this->modx->lexicon('unpublish'),
            'key' => 'unpublish',
        );
        $this->actions['publish'] = array(
            'className' => 'publish',
            'text' => $this->modx->lexicon('publish'),
            'key' => 'publish',
        );
        $this->actions['undelete'] = array(
            'className' => 'undelete',
            'text' => $this->modx->lexicon('undelete'),
            'key' => 'undelete',
        );
        $this->actions['remove'] = array(
            'className' => 'remove',
            'text' => $this->modx->lexicon('collections.children.remove_action'),
            'key' => 'remove',
        );
        $this->actions['delete'] = array(
            'className' => 'delete',
            'text' => $this->modx->lexicon('delete'),
            'key' => 'delete',
        );
        $this->actions['unlink'] = array(
            'className' => 'unlink',
            'text' => $this->modx->lexicon('selections.unlink_action'),
            'key' => 'unlink',
        );
    }

    public function process()
    {
        $beforeQuery = $this->beforeQuery();
        if ($beforeQuery !== true) {
            return $this->failure($beforeQuery);
        }
        $data = $this->getData();

        if (count($this->columnRenderer) > 0) {
            $list = $this->iterateWithRenderer($data);
        } else {
            $list = $this->iterate($data);
        }

        return $this->outputArray($list, $data['total']);
    }

    /**
     * Get the data of the query
     * @return array
     */
    public function getData()
    {
        $data = array();
        $limit = intval($this->getProperty('limit'));
        $start = intval($this->getProperty('start'));

        /* query for chunks */
        $c = $this->modx->newQuery($this->classKey);
        $c = $this->prepareQueryBeforeCount($c);
        $data['total'] = $this->modx->getCount($this->classKey, $c);
        $c = $this->prepareQueryAfterCount($c);

        $gridSort = $this->getProperty('sort');

        $selectionGridSort = (strtolower($gridSort) == 'menuindex') ? 'CollectionSelection`.`menuindex' : $gridSort;

        $c = $this->permanentSort($c, $gridSort, $this->sortBefore);

        if (empty($this->sortType)) {
            $c->sortby('`' . $selectionGridSort . '`', $this->getProperty('dir'));
        } else {
            $c->sortby('CAST(`' . $selectionGridSort . '` as ' . $this->sortType . ')', $this->getProperty('dir'));
        }

        $c = $this->permanentSort($c, $gridSort, $this->sortAfter);

        if ($limit > 0) {
            $c->limit($limit, $start);
        }

        $data['results'] = $this->modx->getCollection($this->classKey, $c);
        return $data;
    }

    public function prepareQueryBeforeCount(xPDOQuery $c)
    {
        $c->leftJoin('CollectionSelection', 'CollectionSelection', 'modResource.id = CollectionSelection.resource');

        $parent = $this->getProperty('parent', null);

        $c->where(array(
            'CollectionSelection.collection' => $parent,
        ));

        $query = $this->getProperty('query', null);
        if (!empty($query)) {
            $c->leftJoin('modUserProfile', 'CreatedByProfile', array('CreatedByProfile.internalKey = modResource.createdby'));
            $c->leftJoin('modUser', 'CreatedBy');

            if ($this->searchQueryTitleOnly) {
                $queryWhere = array(
                    'pagetitle:LIKE' => '%' . $query . '%',
                );
            } else {
                $queryWhere = array(
                    'pagetitle:LIKE' => '%' . $query . '%',
                    'OR:description:LIKE' => '%' . $query . '%',
                    'OR:alias:LIKE' => '%' . $query . '%',
                    'OR:introtext:LIKE' => '%' . $query . '%',
                    'OR:CreatedByProfile.fullname:LIKE' => '%' . $query . '%',
                    'OR:CreatedBy.username:LIKE' => '%' . $query . '%',
                );
            }
            if ($this->searchQueryExcludeTagger == false) {
                $taggerInstalled = $this->modx->collections->getOption('taggerInstalled', null, false);
                if ($taggerInstalled) {
                    $c->leftJoin('TaggerTagResource', 'TagResource', array('TagResource.resource = modResource.id'));
                    $c->leftJoin('TaggerTag', 'Tag', array('Tag.id = TagResource.tag'));

                    array_push($queryWhere, array(
                        'OR:Tag.tag:LIKE' => '%' . $query . '%',
                    ));
                }
            }

            $c->where($queryWhere);
        }
        $filter = $this->getProperty('filter', '');
        switch ($filter) {
            case 'published':
                $c->where(array(
                    'published' => 1,
                    'deleted' => 0,
                ));
                break;
            case 'unpublished':
                $c->where(array(
                    'published' => 0,
                    'deleted' => 0,
                ));
                break;
            case 'deleted':
                $c->where(array(
                    'deleted' => 1,
                ));
                break;
            default:
                $c->where(array(
                    'deleted' => 0,
                ));
                break;
        }

        foreach ($this->tvColumns as $column) {
            $c->leftJoin('modTemplateVarResource', '`TemplateVarResources_' . $column['column'] . '`', '`TemplateVarResources_' . $column['column'] . '`.`contentid` = modResource.id AND `TemplateVarResources_' . $column['column'] . '`.`tmplvarid` = ' . $column['id']);
        }

        return $c;
    }

    public function prepareQueryAfterCount(xPDOQuery $c)
    {

        $c->select($this->modx->getSelectColumns('modResource', 'modResource', '', array('menuindex'), true));
        $c->select($this->modx->getSelectColumns('CollectionSelection', 'CollectionSelection', '', array('menuindex')));
        $c->select($this->modx->getSelectColumns('CollectionSelection', 'CollectionSelection', 'cs_', array('collection')));

        foreach ($this->tvColumns as $column) {
            $c->select(array(
                '`' . $column['column'] . '`' => 'IF(`TemplateVarResources_' . $column['column'] . '`.`value` IS NULL OR`TemplateVarResources_' . $column['column'] . '`.`value` = \'\', "' . $column['default'] . '", `TemplateVarResources_' . $column['column'] . '`.`value`)'
            ));
        }

        $taggerInstalled = $this->modx->collections->getOption('taggerInstalled', null, false);
        if ($taggerInstalled) {
            foreach ($this->taggerColumns as $column) {
                $c->select(array(
                    '`' . $column . '`' => '(SELECT group_concat(t.tag SEPARATOR \', \') FROM ' . $this->modx->getTableName('TaggerTagResource') . ' tr LEFT JOIN ' . $this->modx->getTableName('TaggerTag') . ' t ON t.id = tr.tag LEFT JOIN ' . $this->modx->getTableName('TaggerGroup') . ' tg ON tg.id = t.group WHERE tr.resource = modResource.id AND tg.alias = \'' . preg_replace('/tagger_/', '', $column, 1) . '\' group by t.group)'
                ));
            }
        }

        return $c;
    }

    protected function permanentSort(xPDOQuery $c, $gridSort, $sortOptions)
    {
        $sorts = explode(',', $sortOptions);
        foreach ($sorts as $sort) {
            $sort = explode('=', $sort);
            if (isset($sort[1])) {
                if (($sort[0] != '*') && (strtolower($sort[0]) != strtolower($gridSort))) continue;
            }

            $options = (isset($sort[1])) ? $sort[1] : $sort[0];
            $options = explode(':', $options);
            if (empty($options[0])) continue;

            $options['field'] = (strtolower($options[0]) == 'menuindex') ? 'CollectionSelection`.`menuindex' : $options[0];
            $options['dir'] = empty($options[1]) ? $this->getProperty('dir') : $options[1];
            $options['type'] = empty($options[2]) ? null : $options[2];

            if (empty($options['type'])) {
                $c->sortby('`' . $options['field'] . '`', $options['dir']);
            } else {
                $c->sortby('CAST(`' . $options['field'] . '` as ' . $options['type'] . ')', $options['dir']);
            }
        }

        return $c;
    }

    public function iterateWithRenderer(array $data)
    {
        $list = array();
        $list = $this->beforeIteration($list);
        $this->currentIndex = 0;
        /** @var xPDOObject|modAccessibleObject $object */
        foreach ($data['results'] as $object) {
            if ($this->checkListPermission && $object instanceof modAccessibleObject && !$object->checkPolicy('list')) continue;

            $objectArray = $this->prepareRowWithRenderer($object);

            if (!empty($objectArray) && is_array($objectArray)) {
                $list[] = $objectArray;
                $this->currentIndex++;
            }
        }
        $list = $this->afterIteration($list);
        return $list;
    }

    public function prepareRowWithRenderer(xPDOObject $object)
    {
        $resourceArray = parent::prepareRow($object);

        foreach ($this->columnRenderer as $field => $snippet) {
            $value = isset($resourceArray[$field]) ? $resourceArray[$field] : null;
            $resourceArray[$field] = $this->modx->runSnippet($snippet, array('value' => $value, 'row' => $resourceArray, 'input' => $value, 'column' => $field));
        }

        $resourceArray = $this->prepareSupportFields($resourceArray);
        $resourceArray = $this->prepareActions($resourceArray);
        $resourceArray = $this->prepareMenuActions($resourceArray);

        return $resourceArray;
    }

    public function prepareSupportFields($resourceArray)
    {
        $version = $this->modx->getVersionData();

        $parent = (int)$this->getProperty('parent', null);

        if ($version['major_version'] < 3) {
            $resourceArray['action_edit'] = '?a=30&id=' . $resourceArray['id'] . '&selection=' . $parent;
        } else {
            $resourceArray['action_edit'] = '?a=resource/update&action=post/update&id=' . $resourceArray['id'] . '&selection=' . $parent;
        }

        $this->modx->getContext($resourceArray['context_key']);
        $resourceArray['preview_url'] = $this->modx->makeUrl($resourceArray['id'], $resourceArray['context_key']);

        return $resourceArray;
    }

    public function prepareActions($resourceArray)
    {
        $resourceArray['actions'] = array();

        foreach ($this->buttons as $button) {
            if (isset($this->actions[$button])) {
                switch ($button) {
                    case 'publish':
                        if (empty($resourceArray['published'])) {
                            $resourceArray['actions'][] = $this->actions[$button];
                        }
                        break;
                    case 'unpublish':
                        if (!empty($resourceArray['published'])) {
                            $resourceArray['actions'][] = $this->actions[$button];
                        }
                        break;
                    case 'quickupdate':
                        $resourceArray['actions'][] = $this->actions[$button];
                        break;
                    case 'delete':
                        if (empty($resourceArray['deleted'])) {
                            $resourceArray['actions'][] = $this->actions[$button];
                        }
                        break;
                    case 'undelete':
                        if (!empty($resourceArray['deleted'])) {
                            $resourceArray['actions'][] = $this->actions[$button];
                        }
                        break;
                    case 'remove':
                        if (!empty($resourceArray['deleted'])) {
                            $resourceArray['actions'][] = $this->actions[$button];
                        }
                        break;
                    default:
                        $resourceArray['actions'][] = $this->actions[$button];
                }

            }
        }

        return $resourceArray;
    }

    public function prepareMenuActions($resourceArray)
    {
        $resourceArray['menu_actions'] = array();

        $resourceArray['menu_actions']['view'] = $this->actions['view'];
        $resourceArray['menu_actions']['edit'] = $this->actions['edit'];
        $resourceArray['menu_actions']['quickupdate'] = $this->actions['quickupdate'];

        if (!empty($resourceArray['published'])) {
            $resourceArray['menu_actions']['unpublish'] = $this->actions['unpublish'];
        } else {
            $resourceArray['menu_actions']['publish'] = $this->actions['publish'];
        }

        if (!empty($resourceArray['deleted'])) {
            $resourceArray['menu_actions']['undelete'] = $this->actions['undelete'];
            $resourceArray['menu_actions']['remove'] = $this->actions['remove'];
        } else {
            $resourceArray['menu_actions']['delete'] = $this->actions['delete'];
        }

        $resourceArray['menu_actions']['unlink'] = $this->actions['unlink'];

        return $resourceArray;
    }

    public function iterate(array $data)
    {
        $list = array();
        $list = $this->beforeIteration($list);
        $this->currentIndex = 0;
        /** @var xPDOObject|modAccessibleObject $object */
        foreach ($data['results'] as $object) {
            if ($this->checkListPermission && $object instanceof modAccessibleObject && !$object->checkPolicy('list')) continue;

            $objectArray = $this->prepareRow($object);

            if (!empty($objectArray) && is_array($objectArray)) {
                $list[] = $objectArray;
                $this->currentIndex++;
            }
        }
        $list = $this->afterIteration($list);
        return $list;
    }

    /**
     * @param xPDOObject $object
     * @return array
     */
    public function prepareRow(xPDOObject $object)
    {
        $resourceArray = parent::prepareRow($object);

        $resourceArray = $this->prepareSupportFields($resourceArray);
        $resourceArray = $this->prepareActions($resourceArray);
        $resourceArray = $this->prepareMenuActions($resourceArray);

        return $resourceArray;
    }
}

return 'CollectionsSelectionGetListProcessor';
