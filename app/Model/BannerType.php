<?
App::uses('AppModel', 'Model');
class BannerType extends AppModel {
	var $name = 'BannerType';
	var $useTable = false;
	
	const HTML = 1;
	const IMAGE = 2;
	const SLIDER = 3;
	
	public function getOptions() {
		return array(
			self::HTML => 'HTML-код',
			self::IMAGE => 'Изображение',
			self::SLIDER => 'Слайдер'
		);
	}
}
