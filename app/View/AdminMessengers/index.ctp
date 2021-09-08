<?
	$createTitle = __('Create messenger');
	
	$createURL = $this->Html->url(array('action' => 'edit', 0));
	$actions = $this->PHTableGrid->getDefaultActions('Messenger');
	$actions['table']['add']['href'] = $createURL;
	$actions['table']['add']['label'] = $createTitle;
	
	$backURL = $this->Html->url(array('action' => 'index'));
	$deleteURL = $this->Html->url(array('action' => 'delete')).'/{$id}?model=Messenger&backURL='.urlencode($backURL);
	$actions['row']['delete'] = $this->Html->link('', $deleteURL, array('class' => 'icon-color icon-delete', 'title' => __('Delete record')), __('Are you sure to delete this record?'));
	
    $columns = $this->PHTableGrid->getDefaultColumns('Messenger');
    $columns['Messenger.uid']['label'] = __('UID');

	$title = __('Settings').': '.__('Messengers');
	echo $this->element('admin_title', compact('title'));
?>
<div class="text-center">
    <a class="btn btn-primary" href="<?=$createURL?>">
        <i class="icon-white icon-plus"></i> <?=$createTitle?>
    </a>
</div>
<br/>
<?
    echo $this->PHTableGrid->render('Messenger', compact('actions', 'columns'));
?>