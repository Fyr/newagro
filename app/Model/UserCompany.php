<?
App::uses('AppModel', 'Model');
class UserCompany extends AppModel {
	public $useDbConfig = 'vitacars';
    public $useTable = 'clients_companies';

	public $validate = array(
        // Username is already email
        'company_name' => array(
            'checkNotEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'Field is mandatory',
            ),
        ),
        'company_uuid' => array(
            'checkNotEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'Field is mandatory'
            ),
        ),
        'address' => array(
            'checkNotEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'Field is mandatory',
            )
        ),
        'contact_person' => array(
            'checkNotEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'Field is mandatory',
            )
        ),
        'contact_phone' => array(
            'checkNotEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'Field is mandatory',
            )
        ),
    );
}
