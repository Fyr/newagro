<?php
App::uses('AppController', 'Controller');
App::uses('AppModel', 'Model');
App::uses('Page', 'Model');
App::uses('News', 'Model');
App::uses('SiteArticle', 'Model');
App::uses('Region', 'Model');
App::uses('Category', 'Model');
App::uses('Product', 'Model');
App::uses('User', 'Model');
// App::uses('Curl', 'Vendor');
class PagesController extends AppController {
	public $name = 'Pages';
	public $uses = array('Page', 'News', 'Region', 'Marker', 'Category', 'Product');
	public $helpers = array('ArticleVars', 'Media.PHMedia', 'Core.PHTime', 'Media' /*, 'Recaptcha.Recaptcha'*/);

	public function home() {
		$this->set('isHomePage', true);
		$subdomain = $this->getSubdomainId();
		$conditions = array('News.published' => 1, 'News.subdomain_id' => array(SUBDOMAIN_ALL, $subdomain));
		if ($this->aEvents) {
			$conditions['NOT'] = array('News.id' => Hash::extract($this->aEvents, '{n}.News.id'));
		}
		$aNews = $this->News->find('all', array(
			'conditions' => $conditions,
			'order' => array('News.subdomain_id DESC', 'News.featured DESC', 'News.sorting ASC', 'News.created DESC'),
			'limit' => 3
		));
		$this->set('aHomePageNews', $aNews);

		$this->Product->bindModel(array('hasOne' => array('PMFormData' => array(
			'className' => 'Form.PMFormData',
			'foreignKey' => 'object_id',
			'conditions' => array('PMFormData.object_type' => 'ProductParam'),
			'dependent' => true
		))), false);
		$conditions = array('Product.published' => 1, 'Product.featured_'.Configure::read('domain.zone') => 1);
		$order = array('Product.modified' => 'DESC');
		$aFeaturedProducts = $this->Product->find('all', compact('conditions', 'order'));

		$aArticle = $this->Page->getBySlug('home');
		$aArticle2 = $this->Page->getBySlug('home2');
		$this->set('contentArticle', $aArticle);
		$this->set('contentArticle2', $aArticle2);

		if (!(isset($aArticle['Seo']) & isset($aArticle['Seo']['title']) && $aArticle['Seo']['title'])) {
			$aArticle['Seo']['title'] = $aArticle['Page']['title'];
		}
		$this->seo = $aArticle['Seo'];

		$aRegions = Hash::combine($this->Region->find('all'), '{n}.Region.id', '{n}.Region');
		$aMarkers = $this->Marker->find('all');
		$aMarkers = Hash::combine($aMarkers, '{n}.Marker.id', '{n}.Marker', '{n}.Marker.region_id'); // group by region
		$this->set(compact('aRegions', 'aMarkers', 'aFeaturedProducts'));
	}

	public function show($slug) {
		$aArticle = $this->Page->getBySlug($slug);
		if (!$aArticle) {
			$this->redirect404();
			return;
		}
		$this->set('aArticle', $aArticle);

		if (!(isset($aArticle['Seo']) & isset($aArticle['Seo']['title']) && $aArticle['Seo']['title'])) {
			$aArticle['Seo']['title'] = $aArticle['Page']['title'];
		}
		$this->seo = $aArticle['Seo'];
		$this->currMenu = $slug;
		$this->currLink = $slug;
	}

	public function nonExist() {
		$this->render('/Errors/error400');
	}

	public function region($id) {
		$region = $this->Region->findById($id);
		if (!$region) {
			$this->redirect404();
			return;
		}

		$conditions = array('region_id' => $id);
		$order = 'sorting';
		$aSubdomains = $this->Subdomain->find('all', compact('conditions', 'order'));
		$this->set(compact('region', 'aSubdomains'));
	}

    public function register() {
        $this->loadModel('User');
		if ($this->request->is('post')) {
		    /*
            try {
                if (!$this->_verifyToken($this->request->data('User.token'))) {
                    $recaptchaError = __('Spam protection! Reload page and repeat your actions');
                }
            } catch (Exception $e) {
                $recaptchaError = $e->getMessage();
            }
            */


		    $this->request->data('User.username', $this->request->data('User.email'));
		    $isValid = false;
		    if ($this->request->data('User.group_id') == User::GROUP_COMPANY) {
		        $this->request->data('User.fio', '');
		        $this->request->data('User.phone', '');
		        $this->request->data('User.active', 0);
		        $isValid = $this->User->saveAll($this->request->data);
            } else {
		        $isValid = $this->User->save($this->request->data('User'));
            }

			if ($isValid) {
			    return $this->redirect(array('controller' => 'user', 'action' => 'login'));
			}
		}

		$this->set('accountTypeOptions', $this->User->getAccountTypeOptions());
		$this->layout = 'user_area';
		$this->leftSidebar = false;
	}

	public function forgotPassword() {
	    $this->loadModel('PasswordRecover');
	    $this->loadModel('User');
        if ($this->request->is('post')) {
            $this->PasswordRecover->set($this->request->data);
            if ($this->PasswordRecover->validates()) {
                $password = str_shuffle(substr(md5(Configure::read('Security.salt').date("Y-m-d H:i:s")), 0, 10).'-!$#');
                $id = $this->PasswordRecover->id;
                $data = compact('id', 'password');
                $this->User->save($data, false);
                // TODO: send email to user with new password
            }
        }
        $this->layout = 'user_area';
        $this->leftSidebar = false;
	}

/*
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
        fdebug(compact('_response', 'response'), 'curl.log');
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
    */
}
