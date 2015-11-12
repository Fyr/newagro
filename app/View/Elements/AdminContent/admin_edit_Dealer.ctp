<?
	echo $this->PHForm->input('status', array('label' => false, 'multiple' => 'checkbox', 'options' => array('published' => __('Published', true), 'featured' => __('Featured', true)), 'class' => 'checkbox inline'));
	echo $this->PHForm->input('title', array('id' => 'ArticleTitle', 'onkeyup' => 'article_onChangeTitle()'));
	
	$this->Html->script(array('/Article/js/translit_utf', '/Article/js/edit_slug'), array('inline' => false));
	echo $this->PHForm->input('slug', array('id' => 'ArticleSlug', 'onchange' => 'article_onChangeSlug()'));
?>
<script type="text/javascript">
var slug_EditMode = <?=(($this->request->data($this->PHForm->defaultModel.'.slug'))) ? 'true' : 'false'?>;
</script>
<?
	echo $this->PHForm->input('DealerTable.site_url');
	echo $this->PHForm->input('DealerTable.email');
	echo $this->PHForm->input('teaser');
	echo $this->PHForm->input('DealerTable.address');
	echo $this->PHForm->input('DealerTable.phones');
	echo $this->PHForm->input('DealerTable.work_time');
	
