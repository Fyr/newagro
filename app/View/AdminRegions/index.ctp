<?
	$createTitle = $this->ObjectType->getTitle('create', $objectType);
	$createURL = $this->Html->url(array('action' => 'edit', 0));

	$actions = $this->PHTableGrid->getDefaultActions($objectType);
	$actions['table'] = array();
	$actions['checked'] = array();
	unset($actions['row']['delete']);

	$columns = $this->PHTableGrid->getDefaultColumns($objectType);

	$title = __('Settings').': '.$this->ObjectType->getTitle('index', $objectType);
	echo $this->element('admin_title', compact('title'));
?>
<div class="text-center">
    <a class="btn btn-primary" href="<?=$createURL?>">
        <i class="icon-white icon-plus"></i> <?=$createTitle?>
    </a>
</div>
<br/>
<?
    echo $this->PHTableGrid->render($objectType, compact('actions', 'columns'));
?>