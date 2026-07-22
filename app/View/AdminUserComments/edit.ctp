<div class="span8 offset2">
<?
    $id = $this->request->data('UserComment.id');
    $title = $this->ObjectType->getTitle(($id) ? 'edit' : 'create', $objectType);
    $objectID = $this->request->data('UserComment.site_article_id');

	echo $this->element('admin_title', compact('title'));
    echo $this->PHForm->create($objectType);
    echo $this->PHForm->hidden('UserComment.site_article_id');
    $aTabs = array(
        'General' =>
            $this->PHForm->input('status', array(
                'label' => false, 'multiple' => 'checkbox', 'options' => array('published' => __('Published', true)), 'class' => 'checkbox inline'
            ))
            .$this->PHForm->input('author', array('class' => 'input-medium'))
            .$this->PHForm->input('sorting', array('class' => 'input-small'))
		    .$this->PHForm->input('body'),
    );
	echo $this->element('admin_tabs', compact('aTabs'));
	echo $this->element('Form.form_actions', array('backURL' => $this->Html->url(array('action' => 'index', $objectID))));
    echo $this->PHForm->end();
?>
</div>
