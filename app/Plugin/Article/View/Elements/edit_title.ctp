<?
	$eTitle = '#'.$this->PHForm->defaultModel.'Title';
	$eSlug = '#'.$this->PHForm->defaultModel.'Slug';
	echo $this->PHForm->input('title', array('onkeyup' => "article_onChangeTitle('{$eTitle}', '{$eSlug}')"));
