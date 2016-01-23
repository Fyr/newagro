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
				$Email = new CakeEmail();
				$Email->template('contact_message')->viewVars(compact('aRowset', 'aParams'))
					->emailFormat('html')
					->from('info@'.Configure::read('domain.url'))
					->to(Configure::read('Settings.admin_email'))
					->bcc('fyr.work@gmail.com')
					->subject(Configure::read('domain.title').': '.__('Мessage from Contacts page'))
					->send();
				
				$this->redirect(array('action' => 'success'));
			} else {
				// fdebug('inValid');
			}
		}
		
		$this->set('article', $this->Page->findBySlug('contacts1'));
		$this->set('article2', $this->Page->findBySlug('contacts2'));
		$this->disableCopy = false;
	}
	
	public function success() {
		// $this->set('article', $article);
	}

}
