<?
App::uses('AppModel', 'Model');
class UserCompany extends AppModel {
	// var $useTable = false;

	public $validate = array(
        // Username is already email
        'company_name' => array(
            'checkNotEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Field is mandatory',
            ),
        ),
        'company_uuid' => array(
            'checkNotEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Field is mandatory'
            ),
        ),
        'address' => array(
            'checkNotEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Field is mandatory',
            )
        ),
        'contact_person' => array(
            'checkNotEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Field is mandatory',
            )
        ),
        'contact_phone' => array(
            'checkNotEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Field is mandatory',
            )
        ),
    );
}
