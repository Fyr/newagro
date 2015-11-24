<div class="span8 offset2">
<?
    $id = $this->request->data('Article.id');
    $objectType = $this->request->data('Article.object_type');
    $title = $this->ObjectType->getTitle(($id) ? 'edit' : 'create', $objectType);
    $cat_id = $this->request->data('Article.cat_id');
    $subcat_id = $this->request->data('Article.subcat_id');

	echo $this->element('admin_title', compact('title'));
    echo $this->PHForm->create('Article');
    echo $this->Form->hidden('Seo.id', array('value' => Hash::get($this->request->data, 'Seo.id')));
    $aTabs = array(
        'General' => $this->element('/AdminContent/admin_edit_'.$objectType),
		'Text' => $this->element('Article.edit_body'),
		'SEO' => $this->element('Seo.edit')
    );
    if ($id) {
        $aTabs['Media'] = $this->element('Media.edit', array('object_type' => 'SectionArticle', 'object_id' => $id));
    }
	echo $this->element('admin_tabs', compact('aTabs'));
	echo $this->element('Form.form_actions', array('backURL' => $this->Html->url(array('action' => 'index', $cat_id, $subcat_id))));
    echo $this->PHForm->end();
?>
</div>
