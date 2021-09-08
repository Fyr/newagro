<?php
App::uses('AppModel', 'Model');
class Messenger extends AppModel {

    public function getTypeOptions() {
		return array(
            'skype' => 'Skype',
            'viber' => 'Viber',
            'telegram' => 'Telegram',
            'whatsapp' => 'WhatsApp'
        );
	}

	public function getOptions() {
		$order = 'sorting';
		return $this->find('list', compact('order'));
	}

    public function getUsedList() {
        $sql = "
SELECT ms.*, `Messenger`.* 
FROM (
    SELECT `type`, AVG(sorting) as sorting, (SELECT id FROM messengers AS mu WHERE mt.type = mu.type AND active = 1 ORDER BY used LIMIT 1 ) AS id
    FROM messengers AS mt
    WHERE mt.active = 1 
    GROUP BY `type` 
    ORDER BY sorting) AS ms
INNER JOIN messengers AS `Messenger` ON `Messenger`.id = ms.id
ORDER BY ms.sorting";
        $rowset = $this->query($sql);
        if (!$this->isBot()) {
            foreach($rowset as $row) {
                $sql = 'UPDATE messengers SET used = used + 1 WHERE id = '.$row['Messenger']['id'];
                $this->query($sql);
            }
        }
        return $rowset;
    }
}
