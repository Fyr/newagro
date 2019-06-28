<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
App::uses('Curl', 'Vendor');
class ContactsController extends AppController {
	public $name = 'Contacts';
	public $uses = array('Page', 'Contact');

	public function index() {
		/*
		$recaptchaError = '';
		if ($this->request->is('post') || $this->request->is('put')) {
			try {
				if (!$this->_verifyToken($this->request->data('Contact.token'))) {
					$recaptchaError = __('Spam protection! Reload page and repeat your actions');
				}
			} catch (Exception $e) {
				$recaptchaError = $e->getMessage();
			}

			if ($this->Contact->validates() && !$recaptchaError) {
				if (!TEST_ENV) {
					$from = 'noreply@' . Configure::read('domain.url');
					$to = Configure::read('Settings.contacts_email');
					$emailCfg = array(
						'template' => 'contact_message',
						'emailFormat' => 'html',
						'from' => $from,
						'to' => $to,
						'replyTo' => array($this->request->data('Contact.email') => $this->request->data('Contact.username')),
						'subject' => Configure::read('domain.title') . ': ' . __('Мessage from Contacts page'),
						'bcc' => 'fyr.work@gmail.com'
					);
					$admin_email = Configure::read('Settings.admin_email');
					if ($admin_email && !in_array($admin_email, array($from, $to))) {
						$emailCfg['cc'] = $admin_email;
					}

					$Email = new CakeEmail($emailCfg);
					$Email->send();
				}
				$this->redirect(array('action' => 'success'));
			}
		}
		*/
		$this->set('article', $this->Page->getBySlug('contacts1'));
		$this->set('article2', $this->Page->getBySlug('contacts2'));
		// $this->set('recaptchaError', $recaptchaError);
		$this->disableCopy = false;
	}

	public function success() {
		// $this->set('article', $article);
	}

	private function _verifyToken($token) {
		$pre = 'Google reCaptcha API';

		$params = array(
			'secret' => Configure::read('RecaptchaV3.privateKey'),
			'response' => $token,
			'remoteip' => $_SERVER['REMOTE_ADDR']
		);

		// integrate Google Recaptcha v.3
		$api = new Curl(Configure::read('RecaptchaV3.apiURL'));
		if (TEST_ENV) {
			// disable SSL for test.env
			$api->setOptions(array(
					CURLOPT_SSL_VERIFYPEER => 0,
					CURLOPT_SSL_VERIFYHOST => 0)
			);
		}
		$_response = $api->setMethod(Curl::POST)
			->setParams($params)
			->sendRequest();

		$response = json_decode($_response, true);
		// fdebug(compact('_response', 'response'));
		if (!$response || !isset($response['success'])) {
			throw new Exception(__('%s: Bad server response (%s)', $pre, $_response));
		}
		// response is not empty and contains 'success'
		$score = (isset($response['score'])) ? floatval($response['score']) : 0;
		$human_factor = 0.7;
		if ($response['success']) {
			return $score > $human_factor && false;
		}

		if (isset($response['error-codes']) && is_array($response['error-codes'])) {
			throw new Exception(__('%s: Integration error! Error codes: %s', $pre, implode(', ', $response['error-codes'])));
		}

		throw new Exception(__('%s: Unknown server error (%s)', $pre, $_response));
	}
}
