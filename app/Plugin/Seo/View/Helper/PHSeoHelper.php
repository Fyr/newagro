<?php
/**
 * Wrapper for standart Form helper
 * Customizes default HTML inputs
 */
App::uses('HtmlHelper', 'View/Helper');
App::uses('PaginatorHelper', 'View/Helper');
class PHSeoHelper extends AppHelper {

    public $helpers = array('Html', 'Paginator');

    /**
     * Returns page number if pagination was applied
     */
    public function getPageNumber() {
        $page = intval($this->Paginator->param('page'));
        if ($page) {
            if (!$this->Paginator->numbers()) {
                // only 1 page for this list - no need to add paging title
                return 0;
            }
        }
        return $page;
    }

    public function addPagingTitle($title, $lStyle = false) {
        $page = $this->getPageNumber();
        if ($page) {
			$_title = __d('seo', ' | Page %s', $page);
            $title.= ($lStyle) ? $this->Html->tag('span', $_title, array('class' => 'paging-title')) : $_title;
		}
        return $title;
    }
}