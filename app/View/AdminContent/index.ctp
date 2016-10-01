<?
	$title = $this->ObjectType->getTitle('index', $objectType);
    $createURL = $this->Html->url(array('action' => 'edit', 0, $objectType, $objectID));
    $createTitle = $this->ObjectType->getTitle('create', $objectType);
    
    $actions = $this->PHTableGrid->getDefaultActions($objectType);
    $actions['table']['add']['href'] = $createURL;
    $actions['table']['add']['label'] = $createTitle;
    $actions['row']['edit']['href'] = $this->Html->url(array('action' => 'edit', '~id', $objectType, $objectID));

	$columns = $this->PHTableGrid->getDefaultColumns($objectType);
    if (in_array($objectType, array('Page', 'News', 'Offer'))) {
        $columns[$objectType . '.subdomain_id']['label'] = __('Site');
        //$columns[$objectType.'.subdomain_id']['format'] = 'string';
        foreach ($aRowset as &$row) {
            $row[$objectType]['subdomain_id'] = $aSubdomainOptions[$row[$objectType]['subdomain_id']];
        }
    }
?>
<?=$this->element('admin_title', compact('title'))?>
<div class="text-center">
    <a class="btn btn-primary" href="<?=$createURL?>">
        <i class="icon-white icon-plus"></i> <?=$createTitle?>
    </a>
</div>
<br/>
<?
    echo $this->PHTableGrid->render($objectType, array(
        'baseURL' => $this->ObjectType->getBaseURL($objectType, $objectID),
        'actions' => $actions,
        'columns' => $columns,
        'data' => $aRowset
    ));
?>