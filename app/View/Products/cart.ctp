<?
$this->Html->css(array('grid', '/Icons/css/icons'), array('inline' => false));
$this->Html->script(array('cart', 'vendor/jquery/jquery.cookie'), array('inline' => false));

$aBreadCrumbs = array(__('Home') => '/', __('Cart') => '');
echo $this->element('bread_crumbs', compact('aBreadCrumbs'));
echo $this->element('title', array('title' => __('Cart')));

if ($cartItems) {
?>
<div class="block main clearfix">
	<table class="grid" width="100%" cellpadding="0" cellspacing="0">
		<thead>
		<tr>
			<th>N п/п</th>
			<th>Фото</th>
			<th>Бренд</th>
			<th>Код детали</th>
			<th width="50%">Название</th>
			<th>Кол-во</th>
			<th></th>
		</tr>
		</thead>
		<tbody>
<?
	$class = '';
	$total = 0;
	foreach($aProducts as $i => $article) {
		$this->ArticleVars->init($article, $url, $title, $teaser, $src, '130x100', $featured, $id);
		if (!($title = Hash::get($article, 'Product.title_rus'))) {
			$title = Hash::get($article, 'Product.title');
		}

		$brand_id = Hash::get($article, 'Product.brand_id');
		if (!$src) {
			if (isset($aBrands[$brand_id])) {
				$src = $this->Media->imageUrl($aBrands[$brand_id], '130x100');
			}
		}
		$class = ($class == 'odd') ? 'even' : 'odd';
		$total+= intval($cartItems[$id]);
?>
			<tr id="cart-item_<?=$id?>" class="gridRow <?=$class?>">
				<td align="right"><?=$i + 1?></td>
				<td>
					<a href="<?=$this->Html->url($url)?>">
						<img class="no-fancybox" src="<?=$src?>" alt="<?=$title?>" style="width: 50px"/>
					</a>
				</td>
				<td nowrap="nowrap"><?=Hash::get($aBrands[$brand_id], 'Brand.title')?></td>
				<td><?=Hash::get($article, 'Product.code')?></td>
				<td><?=$this->Html->link($title, $url)?></td>
				<td align="center">
					<input type="text" autocomplete="off" name="cart-qty" value="<?=$cartItems[$id]?>" style="width: 20px; text-align: center"
						   onclick="this.focus()" onfocus="this.select()" onkeyup="Cart.edit(<?=$id?>)" onchange="Cart.edit(<?=$id?>)"
					/>
				</td>
				<td class="nowrap text-center">
					<a class="icon-color icon-delete" href="javascript:;" title="Удалить из корзины" onclick="Cart.remove(<?=$id?>)" style="display: inline-block"></a>
				</td>
			</tr>
<?
	}
	$class = ($class == 'odd') ? 'even' : 'odd';
?>
			<tr class="gridRow <?=$class?>">
				<td colspan="5" align="right" style="padding: 13px 5px"><b>Всего:</b></td>
				<td id="cart-total" align="center"><?=$total?></td>
				<td></td>
			</tr>
		</tbody>
	</table>
	<!--div class="more">
		<a href="#">Пересчитать</a>
	</div-->
</div>
<?=$this->element('title', array('title' => 'Ваши данные для заказа'))?>
<form method="post" action="" id="postForm" class="feedback">
	<div class="block main">
		<p>
			<?=__('Fields with %s are mandatory.', '<span class="star">*</span>')?><br/>
		</p>
		<?
		echo $this->PHForm->create('SiteOrder');
		echo $this->PHForm->input('SiteOrder.username', array('label' => array('text' => '<span class="star">*</span> '.__('Your name'))));
		echo $this->PHForm->input('SiteOrder.email', array('label' => array('text' => '<span class="star">*</span>'.__('E-mail'))));
		echo $this->PHForm->input('SiteOrder.phone', array('type' => 'text', 'label' => array('text' => '<span class="star">*</span> Телефон')));
		echo $this->PHForm->input('SiteOrder.address', array('type' => 'textarea', 'label' => array('text' => '<span class="star">*</span> Адрес')));
		echo $this->PHForm->input('SiteOrder.comment', array('type' => 'textarea', 'label' => array('text' => 'Комментарий к заказу')));
		// echo $this->Form->input('captcha', array('type' => 'hidden', 'label' => array('text' => '<span class="star">*</span> '.__('Spam protection'))));
		echo $this->PHForm->button('Заказать', array('class' => 'submit', 'type' => 'submit'));
		echo $this->PHForm->end();
		?>
	</div>
</form>
<?
} else {
?>
<div class="block main clearfix">
	<p>
		Корзина пуста.<br />
		<br />
		<a href="/"><?=__('Back to home page')?></a>
	</p>
</div>
<?
}
?>
