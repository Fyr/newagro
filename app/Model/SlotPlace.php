<?
App::uses('AppModel', 'Model');
class SlotPlace extends AppModel {
	var $name = 'SlotPlace';
	var $useTable = false;
	
	public function getOptions() {
		return array(
			1 => 'Слот 1 (Осн.контент - Вверху)',
			2 => 'Слот 2 (Осн.контент - Внизу)',
			3 => 'Слот 3 (Левая полоса, Над рубрикатором)',
			4 => 'Слот 4 (Левая полоса, Под рубрикатором)',
			5 => 'Слот 5 (Правая полоса, Вверху)',
			6 => 'Слот 6 (Правая полоса, Внизу)',
		);
	}
}
