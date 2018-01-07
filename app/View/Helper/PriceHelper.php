<?php
App::uses('AppHelper', 'View/Helper');
class PriceHelper extends AppHelper {

	public function format($sum, $lFull = true) {
		$sum = number_format($sum,
			Configure::read('Settings.decimals'),
			Configure::read('Settings.float_div'),
			' '
		);
		$sum = str_replace(' ', Configure::read('Settings.int_div'), $sum); // fix for multibyte int_div (PHP v5.3)
		if ($lFull) {
			$sum = Configure::read('Settings.price_prefix').$sum.Configure::read('Settings.price_postfix');
		}
		return str_replace('$P', $this->symbolP(), $sum);
	}

	public function symbolP() {
		return '<span class="rubl">₽</span>';
	}

	public function getPrice($product, $lForce = false) {
		if ($lForce || in_array($product['Product']['brand_id'], explode(',', Configure::read('Settings.brand_prices'))) ) {
			$price = floatval($product['PMFormData']['fk_' . Configure::read('Settings.fk_price')]);
			$price2 = floatval($product['PMFormData']['fk_' . Configure::read('Settings.fk_price2')]);
			return ($price) ? $price : $price2;
		}
		return null;
	}
}
