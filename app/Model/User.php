<?php
App::uses('AppModel', 'Model');
App::uses('AuthComponent', 'Controller/Component');
class User extends AppModel {
    public $useDbConfig = 'vitacars';
    public $useTable = 'clients';

	const GROUP_USER = 1;
	const GROUP_COMPANY = 2;
	const GROUP_ADMIN = 10;

	public $hasOne = array(
        'UserCompany' => array(
            'className' => 'UserCompany',
            'foreignKey' => 'user_id',
            'conditions' => array('User.group_id' => self::GROUP_COMPANY),
            'dependent' => true
        ),
    );

	public $validate = array(
		'email' => array(
			'checkNotEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Field is mandatory',
			),
			'checkEmail' => array(
				'rule' => 'email',
				'message' => 'Email is incorrect'
			),
			'checkIsUnique' => array(
				'rule' => 'isUnique',
				'message' => 'This email is already in use'
			)
		),
		'password' => array(
			'checkNotEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Field is mandatory'
			),
			'checkMinLen' => array(
				'rule' => array('minLength', '10'),
				'message' => 'The password must be minimum 10 characters'
			),
			'checkMatchPassword' => array(
				'rule' => array('matchPassword'),
				'message' => 'Your password and its confirmation do not match',
			)
		),
		'password_confirm' => array(
			'checkNotEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Field is mandatory',
			)
		),
		'fio' => array(
			'checkNotEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Field is mandatory',
			)
		),
		'phone' => array(
			'checkNotEmpty' => array(
				'rule' => 'notEmpty',
				'message' => 'Field is mandatory',
			),
			'checkIsUnique' => array(
				'rule' => 'isUnique',
				'message' => 'This phone is already in use'
			)
		),
	);

	public function matchPassword($data) {
		if ($data['password'] == $this->data['User']['password_confirm']) {
			return true;
		}
		$this->invalidate('password_confirm', __('Your password and its confirmation do not match'));
		return false;
	}

	public function beforeValidate($options = array()) {
		if (Hash::get($options, 'validate')) {
			if (!Hash::get($this->data, 'User.password')) {
				$this->validator()->remove('User.password');
				$this->validator()->remove('User.password_confirm');
			}
			if (Hash::get($this->data, 'User.group_id') == self::GROUP_COMPANY) {
			    // remove user profile validators
			    $this->validator()->remove('fio');
			    $this->validator()->remove('phone');
			}
		}
		return true;
	}

	private function getZone() {
	    return Configure::read('domain.zone');
	}

	public function beforeFind($query) {
        $query['conditions']['User.zone'] = $this->getZone();
        return $query;
    }

	public function beforeSave($options = array()) {
	    if (isset($this->data['User'])) {
            if (isset($this->data['User']['password'])) {
                $this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
            }
            $this->data['User']['zone'] = $this->getZone();
		}
		return true;
	}

	public function getAccountTypeOptions() {
		return array(
			self::GROUP_USER => __('Regular user'),
			self::GROUP_COMPANY => __('Legal entity'),
		);
	}
}
