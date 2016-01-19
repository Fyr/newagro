<?
	$objectType = $this->request->data('Article.object_type');
	echo $this->PHForm->input('status', array(
		'label' => false,
		'multiple' => 'checkbox',
		'options' => array('published' => __('Published', true), 'featured' => __('Featured', true)),
		'class' => 'checkbox inline'
	));
	echo $this->element('Article.edit_title');
	echo $this->element('Article.edit_slug');
	if ($this->request->data('Article.cat_id')) {
		echo $this->PHForm->input('cat_id', array(
			'options' => $aCategoryOptions,
			'onchange' => 'onChangeCategory()',
			'label' => array('class' => 'control-label', 'text' => 'Вложенность')
		));
	}

	echo $this->PHForm->input('teaser');
	echo $this->PHForm->input('sorting', array('class' => 'input-small'));
?>
