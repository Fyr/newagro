<div class="span8 offset2">
<?
    $id = $this->request->data('Dealer.id');
    $title = $this->ObjectType->getTitle(($id) ? 'edit' : 'create', $objectType);
    
    $objectID = '';
	echo $this->element('admin_title', compact('title'));
    echo $this->PHForm->create('Dealer');
    echo $this->Form->hidden('Seo.id', array('value' => Hash::get($this->request->data, 'Seo.id')));
    $aTabs = array(
        'General' => $this->element('/AdminContent/admin_edit_'.$objectType),
		'Text' => $this->element('Article.edit_body'),
		'SEO' => $this->element('Seo.edit')
    );
    if ($id) {
        $aTabs['Media'] = $this->element('Media.edit', array('object_type' => 'Page', 'object_id' => $id));
    }
	echo $this->element('admin_tabs', compact('aTabs'));
	echo $this->element('Form.form_actions', array('backURL' => $this->ObjectType->getBaseURL($objectType, $objectID)));
    echo $this->PHForm->end();
?>
</div>
<script type="text/javascript">
$(document).ready(function(){
	// var $grid = $('#grid_FormField');
});
</script>