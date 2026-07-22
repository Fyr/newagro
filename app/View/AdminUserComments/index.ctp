<?
	$title = Hash::get($siteArticle, 'SiteArticle.title').': '.$this->ObjectType->getTitle('index', $objectType);
    $createURL = $this->Html->url(array('action' => 'edit', 0, $objectID));
    $createTitle = $this->ObjectType->getTitle('create', $objectType);

    $actions = $this->PHTableGrid->getDefaultActions($objectType);
    $actions['table']['add']['href'] = $createURL;
    $actions['table']['add']['label'] = $createTitle;
    $actions['row']['edit']['href'] = $this->Html->url(array('action' => 'edit', '~id'));

    $backURL = $this->Html->url(array('action' => 'index', $objectID));
    $deleteURL = $this->Html->url(array('action' => 'delete')).'/{$id}?model=UserComment&backURL='.urlencode($backURL);
    $actions['row']['delete'] = $this->Html->link('', $deleteURL, array('class' => 'icon-color icon-delete', 'title' => __('Delete record')), __('Are you sure to delete this record?'));

	$columns = $this->PHTableGrid->getDefaultColumns($objectType);

	foreach($aRowset as &$row) {
	    $row['UserComment']['body'] = nl2br($row['UserComment']['body']);
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
<style>
.grid td.format-text span p { margin: 0 }
</style>
