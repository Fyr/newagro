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

	public function calcPrice($price, $discount) {
	    return $price * (100 - $discount) / 100;
	}

	public function getPrice($product, $aBrandDiscounts = array(), $isApplyDiscount = true) {
	    $brand_id = $product['Product']['brand_id'];
	    $fk_price = Configure::read('Settings.fk_price');
	    $fk_price2 = Configure::read('Settings.fk_price2');
		if ($fk_price && $this->isShowPrice($product, $aBrandDiscounts)) {
			$price = floatval($product['PMFormData']['fk_'.$fk_price]);
			$price2 = floatval($product['PMFormData']['fk_'.$fk_price2]);
			$finalPrice = ($price) ? $price : $price2;

            $discount = $this->getBrandDiscount($product, $aBrandDiscounts);
			if ($discount && $isApplyDiscount) {
			    $finalPrice = $this->calcPrice($finalPrice, $discount);
			}
            return $finalPrice;
		}
		return null;
	}

    // Return brand IDs that are allowed to show its prices
	private function getBrandsWithPrices() {
	    $brand_ids = Configure::read('Settings.brand_prices');
	    return ($brand_ids) ? explode(',', $brand_ids) : array();
	}

	// return discount of current client for this brand if any
	// if user is not logged in, there will be no discounts
	public function getBrandDiscount($product, $aBrandDiscounts) {
	    $brand_id = Hash::get($product, 'Product.brand_id');
	    return (isset($aBrandDiscounts[$brand_id])) ? $aBrandDiscounts[$brand_id] : 0;
	}

    // returns true if price is available for this product
    // returns true, if product's brand is in a list of brands with available prices or client has a brand discount for product's brand
	public function isShowPrice($product, $aBrandDiscounts) {
        $productBrand_id = Hash::get($product, 'Product.brand_id');
	    $brandsWithPrices_ids = $this->getBrandsWithPrices();
	    return in_array($productBrand_id, $this->getBrandsWithPrices()) || $this->getBrandDiscount($product, $aBrandDiscounts);
	}
}
