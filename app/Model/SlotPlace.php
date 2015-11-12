<?
App::uses('AppModel', 'Model');
class SlotPlace extends AppModel {
	var $name = 'SlotPlace';
	var $useTable = false;
	
	public function getOptions() {
		return array(
			1 => 'Слот 1 (Осн.контент - Вверху)',
			2 => 'Слот 2 (Осн.контент - Внизу)',
			3 => 'Слот 3 (Левая полоса, Под рубрикатором)',
			4 => 'Слот 4 (Правая полоса, Вверху)',
			5 => 'Слот 5 (Правая полоса, Внизу)',
		);
	}
}
