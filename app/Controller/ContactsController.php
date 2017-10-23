<?php
App::uses('AppController', 'Controller');
App::uses('CakeEmail', 'Network/Email');
class ContactsController extends AppController {
	public $name = 'Contacts';
	public $uses = array('Page', 'Contact');
	public $components = array('Recaptcha.Recaptcha');
	public $helpers = array('Recaptcha.Recaptcha');

	public function index() {
		if ($this->request->is('post') || $this->request->is('put')) {
			$lCaptchaValid = $this->Recaptcha->verify();
			if (!$lCaptchaValid) {
				$this->set('recaptchaError', $this->Recaptcha->error);
			}
			if ($this->Contact->validates() && $lCaptchaValid) { //
				$from = 'noreply@'.Configure::read('domain.url');
				$to = Configure::read('Settings.contacts_email');
				$emailCfg = array(
					'template' => 'contact_message',
					'emailFormat' => 'html',
					'from' => $from,
					'to' => $to,
					'replyTo' => array($this->request->data('Contact.email') => $this->request->data('Contact.username')),
					'subject' => Configure::read('domain.title').': '.__('Мessage from Contacts page'),
					'bcc' => 'fyr.work@gmail.com'
				);
				$admin_email = Configure::read('Settings.admin_email');
				if ($admin_email && !in_array($admin_email, array($from, $to))) {
					$emailCfg['cc'] = $admin_email;
				}

				$Email = new CakeEmail($emailCfg);
				$Email->send();

				$this->redirect(array('action' => 'success'));
			} else {
				// captcha is invalid
			}
		}
		
		$this->set('article', $this->Page->getBySlug('contacts1'));
		$this->set('article2', $this->Page->getBySlug('contacts2'));
		$this->disableCopy = false;
	}
	
	public function success() {
		// $this->set('article', $article);
	}

}
