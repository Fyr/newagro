<?php
App::uses('AppModel', 'Model');

class PartTradeApi extends AppModel {
	public $useTable = false;
	
	private function writeLog($actionType, $data = ''){
		if (Configure::read('PartTradeApi.txtLog')) {
			$string = date('d-m-Y H:i:s') . ' ' . $actionType . ' ' . $data;
			file_put_contents(Configure::read('PartTradeApi.log'), $string . "\r\n", FILE_APPEND);
		}
	}
	
	private function sendRequest($method, $data = array()) {
		error_reporting(0);
		ini_set('default_socket_timeout', 30);
		$soapClient = new SoapClient(Configure::read('PartTradeApi.url'), array('trace' => 1));
		$response = (array) $soapClient->$method($data);
		
		$this->writeLog('REQUEST', json_encode($data)."\r\n".$soapClient->__getLastRequest());
		$this->writeLog('RESPONSE', json_encode($response));
		
		if (!$response) {
			throw new Exception('PartTradeAPI: No response from server');
		}
		return $response;
	}
	
	public function getSuggests($article) {
		$data = $this->sendRequest('getPartNumbers', $article);
		if (!isset($data['partNumberContainer'])) {
			throw new Exception('PartTradeAPI: Bad server response');
		}
		$data['partNumberContainer'] = (array) $data['partNumberContainer'];
		if (!isset($data['partNumberContainer'][0])) {
			$data['partNumberContainer'] = array($data['partNumberContainer']);
		}
		foreach($data['partNumberContainer'] as $item) {
			$item = (array) $item;
			$aData[] = array(
				'provider' => 'PartTrade',
				'provider_data' => $item,
				'brand' => $item['manufacturerName'],
				'brand_logo' => '',
				'partnumber' => $item['partNumber'],
				'image' => '',
				'title' => $item['partDescription'],
				'title_descr' => ''
			);
		}
		return $aData;
	}
	
	/**
	 * Получить цены поставщиков
	 *
	 * @param string $article - номер детали
	 * @param int $brand - производитель
	 * @return array
	 */
	public function getPrices($article, $brand) {
		$params = array(
			'partNumberPattern' => $article,
			'manufacturerName' => $brand,
			'userCredentials' => array(
				'username' => Configure::read('PartTradeApi.username'),
				'password' => Configure::read('PartTradeApi.password')
			)
		);
		$data = $this->sendRequest('getParts', $params);
		if (!isset($data['partContainer'])) {
			throw new Exception('PartTradeAPI: Bad server response');
		}
		$aData = array();
		$data['partContainer'] = (array) $data['partContainer'];
		if (!isset($data['partContainer'][0])) {
			$data['partContainer'] = array($data['partContainer']);
		}
		foreach($data['partContainer'] as $item) {
			$item = (array) $item;
			$offerType = GpzOffer::ANALOG;
			if ($item['partType'] == 'MATCH') {
				$offerType = GpzOffer::ORIGINAL;
			}
		
			$aData[] = array(
				'provider' => 'PartTrade',
				'provider_data' => $item,
				'offer_type' => $offerType,
				'brand' => $item['manufacturerName'],
				'brand_logo' => '',
				'partnumber' => $item['partNumber'],
				'image' => '',
				'title' => $item['partDescription'],
				'title_descr' => '',
				'qty' => $item['availableAmount'],
				'qty_descr' => 'Минимальный заказ: '.$item['minimalOrder'],
				'qty_order' => '',
				'price' => $this->getPrice($item), 
				'price2' => $this->getPrice2($item),
				'price_orig' => $item['rurPrice'].' RUR',
				'price_descr' => 'Цены поставщиков в RUR. Перевод в BYR по курсу '.Configure::read('Settings.xchg_rur'),
				'provider_descr' => 'Поставщик: PartTrade'
			);
		}
		return $aData;
	}
	
	/**
	 * Оригинальная цена в BYR без наценки
	 */
	private function getPrice($item) {
		$price = floatval($item['rurPrice']);
		return round(Configure::read('Settings.xchg_rur') * $price, -2); // переводим в BYR по курсу из настроек
	}
	
	/**
	 * Цена в BYR с наценкой
	 */
	private function getPrice2($item) {
		$priceRatio = 1 + (Configure::read('Settings.pt_price_ratio')/100);
		return round($priceRatio * $this->getPrice($item), -2);
	}
}
