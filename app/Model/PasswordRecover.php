<?
App::uses('AppModel', 'Model');
class PasswordRecover extends AppModel {
	public $useTable = 'users';

	public $validate = array(
        'email' => array(
            'checkNotEmpty' => array(
                'rule' => 'notBlank',
                'message' => 'Field is mandatory',
            ),
            'checkEmail' => array(
                'rule' => 'email',
                'message' => 'Email is incorrect'
            ),
            'checkEmailExists' => array(
                'rule' => array('emailExists'),
                'message' => 'Email is not registered',
            )
        )
    );

    public function emailExists($data) {
        $email = Hash::get($data, 'email');
        if ($email) {
            $user = $this->findByEmail($data['email']);
            if ($user) {
                $this->id = $user['PasswordRecover']['id'];
                return true;
            }
        }
        // $this->invalidate('email', __('Email is not registered'));
        return false;
    }

}
