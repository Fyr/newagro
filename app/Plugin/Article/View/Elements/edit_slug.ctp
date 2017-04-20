<?
	$this->Html->script(array('/Article/js/translit_utf', '/Article/js/edit_slug'), array('inline' => false));

	$eSlug = '#'.$this->PHForm->defaultModel.'Slug';
	echo $this->PHForm->input('slug', array('onchange' => "article_onChangeSlug('{$eSlug}')"));
?>
<script type="text/javascript">
var slug_EditMode = <?=(($this->request->data($this->PHForm->defaultModel.'.slug'))) ? 'true' : 'false'?>; // <?=$this->PHForm->defaultModel?>

</script>

