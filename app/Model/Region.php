<?
App::uses('AppModel', 'Model');
class Region extends AppModel
{
    public function getOptions() {
        return $this->find('list');
    }
}