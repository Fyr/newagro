<?php
App::uses('AppModel', 'Model');
class SiteOrder extends AppModel {
	public $useDbConfig = 'vitacars';
	public $validate = array(
		'username' => array(
			'checkNotEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Field cannot be blank',
			),
			'checkNameLen' => array(
				'rule' => array('between', 3, 50),
				'message' => 'The name must be between 3 and 50 characters'
			),
		),
		'email' => array(
			'checkNotEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Field cannot be blank',
			),
			'checkEmail' => array(
				'rule' => 'email',
				'message' => 'Email is incorrect'
			)
		),
		'phone' => array(
			'checkNotEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Field cannot be blank'
			)
		),
		'address' => array(
			'checkNotEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Field cannot be blank'
			)
		)
	);
}
