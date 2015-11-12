<?
	if ($this->Paginator->numbers()) {
		// fdebug($this->request->params);
		$this->Paginator->options(array('url' => array(
			'objectType' => $this->request->param('objectType'),
			'category' => $this->request->param('category')
		)));
		
		// $this->Paginator->options(array('url' => $options));
?>
<div class="pagination">
	Страницы: <?=$this->Paginator->numbers(array('separator' => ' '))?>
</div>
<?
	}
?>