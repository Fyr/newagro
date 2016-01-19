<?
    $createURL = $this->Html->url(array('action' => 'edit', 0, $cat_id));
    $createTitle = $this->ObjectType->getTitle('create', $objectType);
    
    $actions = $this->PHTableGrid->getDefaultActions($objectType);
    $actions['table']['add']['href'] = $createURL;
    $actions['table']['add']['label'] = $createTitle;

    $backUrl = $this->Html->url(array('action' => 'index', $cat_id));
    $delUrl = $this->Html->url(array('action' => 'delete', '~id')).'?model='.$objectType.'&backURL='.$backUrl;
    $actions['row']['delete'] = $this->Html->link('', $delUrl, array('class' => 'icon-color icon-delete', 'title' => __('Delete record')), __('Are you sure to delete this record?'));

    $actions['row']['edit']['href'] = $this->Html->url(array('action' => 'edit', '~id'));
    $actions['row']['edit']['href'] = $this->Html->url(array('action' => 'edit', '~id'));
    if (!$cat_id) {
        $actions['row'][] = array(
            'label' => 'Статьи в данной категории',
            'class' => 'icon-color icon-open-folder',
            'href' => $this->Html->url(array('action' => 'index', '~id'))
        );
    }
	$columns = $this->PHTableGrid->getDefaultColumns($objectType);

    $title = $this->ObjectType->getTitle('index', $objectType);
    if ($cat_id) {
        // $aSubcategoryOptions = Hash::combine($aSubcategoryOptions, '{n}.RepairArticle.id', '{n}.RepairArticle.title');
        $title.= ': '.$aCategoryOptions[$cat_id];
    }
    echo $this->element('admin_title', compact('title'));
?>
<div class="text-center">
    <a class="btn btn-primary" href="<?=$createURL?>">
        <i class="icon-white icon-plus"></i> <?=$createTitle?>
    </a>
</div>
<br/>
<?
    echo $this->PHTableGrid->render($objectType, array(
        'baseURL' => $this->Html->url(array('action' => 'index', $cat_id)),
        'actions' => $actions,
        'columns' => $columns,
        'data' => $aRowset
    ));
?>