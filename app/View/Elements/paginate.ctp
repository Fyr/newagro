<?
	if ($this->Paginator->numbers()) {
		$options = array(
			'objectType' => $this->request->param('objectType'),
			'category' => $this->request->param('category'),
			'subcategory' => $this->request->param('subcategory')
		);
		if ($filial = Configure::read('domain.filial')) {
			$options['filial'] = $filial;
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