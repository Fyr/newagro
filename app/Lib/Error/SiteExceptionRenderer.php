<?
App::uses('ExceptionRenderer', 'Error');
class SiteExceptionRenderer extends ExceptionRenderer {

    public function render() {
        $this->controller->set('title_for_layout', __('Error!'));
        if (TEST_ENV) {
            parent::render();
        } else {
            if ($this->method !== 'error500') {
                $this->method = 'error400';
            }
            parent::render();
        }
    }

}
