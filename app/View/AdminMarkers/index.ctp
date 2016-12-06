<?
    $createTitle = $this->ObjectType->getTitle('create', 'Marker');

    $createURL = $this->Html->url(array('action' => 'edit', 0));
    $actions = $this->PHTableGrid->getDefaultActions('Marker');
    $actions['table']['add']['href'] = $createURL;
    $actions['table']['add']['label'] = $createTitle;
    $backURL = $this->Html->url(array('action' => 'index'));
    $deleteURL = $this->Html->url(array('action' => 'delete')).'/{$id}?model=Marker&backURL='.urlencode($backURL);
    $actions['row']['delete'] = $this->Html->link('', $deleteURL, array('class' => 'icon-color icon-delete', 'title' => __('Delete record')), __('Are you sure to delete this record?'));

    $columns = $this->PHTableGrid->getDefaultColumns('Marker');

    $columns['Marker.region_id']['label'] = __('Region');
    $columns['Marker.region_id']['format'] = 'select';
    $columns['Marker.region_id']['options'] = $aRegions;
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
    echo $this->PHTableGrid->render('Marker', compact('actions', 'columns'));
?>