<?
	echo $this->PHForm->input('status', array('label' => false, 'multiple' => 'checkbox', 'options' => array('published' => __('Published', true)), 'class' => 'checkbox inline'));
	echo $this->element('Article.edit_title');
	echo $this->element('Article.edit_slug');
	echo $this->PHForm->input('url');
	echo $this->PHForm->input('descr');
	echo $this->PHForm->input('sorting', array('class' => 'input-small'));
	
