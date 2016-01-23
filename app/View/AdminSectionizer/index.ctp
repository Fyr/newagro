<?
    $createURL = $this->Html->url(array('action' => 'edit', 0, $cat_id, $subcat_id));
    $createTitle = $this->ObjectType->getTitle('create', $objectType);
    
    $actions = $this->PHTableGrid->getDefaultActions($objectType);
    $actions['table']['add']['href'] = $createURL;
    $actions['table']['add']['label'] = $createTitle;
    $actions['row']['edit']['href'] = $this->Html->url(array('action' => 'edit', '~id'));
    $actions['row']['edit']['href'] = $this->Html->url(array('action' => 'edit', '~id'));
    $backUrl = $this->Html->url(array('action' => 'index', $cat_id, $subcat_id));
    $delUrl = $this->Html->url(array('action' => 'delete', '~id')).'?model='.$objectType.'&backURL='.$backUrl;
    $actions['row']['delete'] = $this->Html->link('', $delUrl, array('class' => 'icon-color icon-delete', 'title' => __('Delete record')), __('Are you sure to delete this record?'));
    if (!$subcat_id) {
        $actions['row'][] = array(
            'label' => 'Статьи в данной категории',
            'class' => 'icon-color icon-open-folder',
            'href' => $this->Html->url(array('action' => 'index', $cat_id, '~id'))
        );
    }
	$columns = $this->PHTableGrid->getDefaultColumns($objectType);

    $title = $aCategoryOptions[$cat_id];
    if ($subcat_id) {
        $aSubcategoryOptions = Hash::combine($aSubcategoryOptions, '{n}.SectionArticle.id', '{n}.SectionArticle.title');
        $title.= ': '.$aSubcategoryOptions[$subcat_id];
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
        'baseURL' => $this->Html->url(array('action' => 'index', $cat_id, $subcat_id)),
        'actions' => $actions,
        'columns' => $columns,
        'data' => $aRowset
    ));
?>