<?
	$title = $this->ObjectType->getTitle('index', $objectType);
    $createURL = $this->Html->url(array('action' => 'edit', 0));
    $createTitle = $this->ObjectType->getTitle('create', $objectType);
    
    $actions = $this->PHTableGrid->getDefaultActions($objectType);
    $actions['table']['add']['href'] = $createURL;
    $actions['table']['add']['label'] = $createTitle;
    $actions['row']['edit']['href'] = $this->Html->url(array('action' => 'edit', '~id'));

	$columns = $this->PHTableGrid->getDefaultColumns($objectType);
	$columns = array(
		'Catalog.title' => $columns['Catalog.title'],
		'Catalog.url' => $columns['Catalog.url'],
		'Catalog.file' => array(
			'key' => 'file',
			'label' => __('File'),
			'format' => 'string'
		),
		'Catalog.published' => $columns['Catalog.published'],
		'Catalog.sorting' => $columns['Catalog.sorting']
	);
	
	foreach($aRowset as &$row) {
		$file = '';
		if (isset($row['Media']) && $row['Media']) {
			foreach($row['Media'] as $media) {
				if ($media['media_type'] == 'raw_file') {
					$file = $media['file'].$media['ext'];
					$url = $this->PHMedia->MediaPath->getRawUrl($media['object_type'], $media['id'], $file);
					$file = $this->Html->link($file, $url, array('target' => '_blank'));
				}
			}
		}
		$row['Catalog']['file'] = $file;
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
        'baseURL' => $this->ObjectType->getBaseURL($objectType),
        'actions' => $actions,
        'columns' => $columns,
        'data' => $aRowset
    ));
?>