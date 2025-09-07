<?php
App::uses('AdminController', 'Controller');
App::uses('Settings', 'Model');
App::uses('Brand', 'Model');
App::uses('PMFormField', 'Form.Model');
class AdminSettingsController extends AdminController {
    public $name = 'AdminSettings';
    public $uses = array('Settings', 'Form.PMFormField', 'Brand');

    public function beforeFilter() {
		parent::beforeFilter();

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data('Settings.id', 1);
            $this->Settings->save($this->request->data);
            $this->setFlash(__('Settings are saved'), 'success');
            return $this->redirect(array('action' => $this->request->action));
        }
        $this->request->data = $this->Settings->getData();
	}

    public function index() {
    }

    /*
     * Настройки контактов перенесены в субдомены
    public function contacts() {
    }
    */

    public function prices() {
        $aPrices = $this->PMFormField->find('list', array(
            'fields' => array('id', 'label'),
            'conditions' => array('is_price' => 1),
            'order' => array('label' => 'ASC')
        ));

        $this->paginate = array(
            'Brand' => array(
                'fields' => array('title'),
                'conditions' => array('published' => 1, 'is_fake' => 0),
                'order' => array('title' => 'asc')
            )
        );
        $this->PCTableGrid->paginate('Brand');
        $this->set(compact('aPrices'));
    }
}
