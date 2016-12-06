<?php
App::uses('AppHelper', 'View/Helper');
class ObjectTypeHelper extends AppHelper {
    public $helpers = array('Html');
    
    private function _getTitles() {
        $Titles = array(
            'index' => array(
                'Article' => __('Articles'),
                'Page' => __('Static pages'),
                'News' => __('News'),
                'Offer' => __('Hot Offers'),
                'Motor' => __('Machinery'),
                'SubcategoryArticle' => __('Article subcategories'),
                'Banner' => __('Banners'),
                'Dealer' => __('Dealers'),
                'Catalog' => __('Catalogs'),
                'CategoryProduct' => __('Product categories'),
                'Product' => __('Products'),
                'Brand' => __('Brands'),
                'RepairArticle' => __('Repair articles'),
                'Subdomain' => __('Subdomains'),
                'MachineTool' => __('Machine tools'),
                'Region' => __('Regions'),
                'Marker' => __('Markers')
            ), 
            'create' => array(
                'Article' => __('Create Article'),
                'Page' => __('Create Static page'),
                'News' => __('Create News article'),
                'Offer' => __('New offer'),
                'Motor' => __('New motor'),
                'SubcategoryArticle' => __('Create Article subcategory'),
                'Subcategory' => __('Create Subcategory'),
                'Banner' => __('Create banner'),
                'Dealer' => __('New dealer'),
                'Catalog' => __('New catalog'),
                'CategoryProduct' => __('Create Product category'),
                'Product' => __('Create Product'),
                'Subdomain' => __('Create subdomain'),
                'MachineTool' => __('Create machine tool'),
                'Region' => __('Create region'),
                'Marker' => __('Create marker')
            ),
            'edit' => array(
                'Article' => __('Edit Article'),
                'Page' => __('Edit Static page'),
                'News' => __('Edit News article'),
                'Offer' => __('Edit offer'),
                'Motor' => __('Edit motor'),
                'SubcategoryArticle' => __('Edit Article subcategory'),
                'Subcategory' => __('Edit Subcategory'),
                'Banner' => __('Edit banner'),
                'Dealer' => __('Edit dealer'),
                'Catalog' => __('Edit catalog'),
                'CategoryProduct' => __('Edit Product category'),
                'Product' => __('Edit Product'),
                'Subdomain' => __('Edit subdomain'),
                'MachineTool' => __('Edit machine tool'),
                'Region' => __('Edit region'),
                'Marker' => __('Edit marker')
            ),
            'view' => array(
            	'Article' => __('View Article'),
            	'News' => __('View News article'),
            	'Product' => __('View product'),
            	'Brand' => __('View brand'),
                // 'MachineTool' => __('View machine tool')
            )
        );
        return $Titles;
    }
    
    public function getTitle($action, $objectType) {
        $aTitles = $this->_getTitles();
        return (isset($aTitles[$action][$objectType])) ? $aTitles[$action][$objectType] : $aTitles[$action]['Article'];
    }
    
    public function getBaseURL($objectType, $objectID = '') {
        return $this->Html->url(array('action' => 'index', $objectType, $objectID));
    }
}