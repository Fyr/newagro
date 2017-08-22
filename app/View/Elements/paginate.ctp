<?
	if ($this->Paginator->numbers()) {
		$options = array(
			'objectType' => $this->request->param('objectType'),
			'category' => $this->request->param('category'),
			'subcategory' => $this->request->param('subcategory')
		);
		if ($objectType == 'products' && Configure::read('domain.category')) {
			$options['subdomain'] = 1;
		}
		if ($this->request->query) {
			$options['?'] = $this->request->query;
		}
		$this->Paginator->options(array('url' => $options));
?>
<div class="pagination">
	Страницы: <?=$this->Paginator->numbers(array('separator' => ' '))?>
</div>
<?
	}
?>