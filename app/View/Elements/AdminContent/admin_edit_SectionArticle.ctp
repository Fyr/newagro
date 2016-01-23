<?
	echo $this->PHForm->input('status', array(
		'label' => false,
		'multiple' => 'checkbox',
		'options' => array('published' => __('Published', true), 'featured' => __('Featured', true)),
		'class' => 'checkbox inline'
	));
	echo $this->PHForm->input('section', array('label' => array('class' => 'control-label', 'text' => 'Название (рубрикатор)')));
	echo $this->element('Article.edit_title');
	echo $this->element('Article.edit_slug');
	echo $this->PHForm->input('cat_id', array('options' => $aCategoryOptions, 'onchange' => 'onChangeCategory()', 'label' => array('class' => 'control-label', 'text' => __('Section'))));
	$aSubcategoryOptions = Hash::combine($aSubcategoryOptions, '{n}.SectionArticle.id', '{n}', '{n}.SectionArticle.cat_id');
?>
	<div class="control-group">
		<label class="control-label" for="ArticleSubcatId">Вложенность</label>
		<div class="controls">
			<select id="ArticleSubcatId" name="data[Article][subcat_id]">
<?
	foreach($aCategoryOptions as $cat_id => $title) {
?>
				<optgroup id="<?=$cat_id?>" label="<?=$title?>" style="display: none">
					<option value="0"> - Категория - </option>
<?
		foreach($aSubcategoryOptions[$cat_id] as $article) {
			$id = $article['SectionArticle']['id'];
			$title = $article['SectionArticle']['title'];
            $selected = ($this->request->data('Article.subcat_id') == $article['SectionArticle']['id']) ? 'selected="selected"' : '';
?>
					<option value="<?=$id?>" <?=$selected?>><?=$title?></option>
<?
		}
?>
				</optgroup>
<?
	}
?>
			</select>
		</div>
	</div>
<?
	// echo $this->PHForm->input('subcat_id', array('options' => $aSubcategoryOptions, 'style' => 'width: auto', 'label' => array('class' => 'control-label', 'text' => __('Section'))));
	echo $this->PHForm->input('teaser');
	echo $this->PHForm->input('sorting', array('class' => 'input-small'));
?>
<script type="text/javascript">
function onChangeCategory() {
	var cat_id = $('#ArticleCatId').val();
	$('#ArticleSubcatId optgroup').hide();
	$('#ArticleSubcatId optgroup#' + cat_id).show();
	$('#ArticleSubcatId').val(0);
}
$(function(){
	onChangeCategory();
<?
    if ($subcat_id = $this->request->data('Article.subcat_id')) {
?>
    $('#ArticleSubcatId').val(<?=$subcat_id?>);
<?
    }
?>
});
</script>
