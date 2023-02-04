<?php
App::uses('AppModel', 'Model');
class NotifyMessage extends AppModel {
	public $useDbConfig = 'vitacars';
	public $useTable = 'messages';
}
