<?
App::uses('Router', 'Cake/Routing');
class SiteRouter extends Router {

	static public function getObjectType($article) {
		list($objectType) = array_keys($article);
		return $objectType;
	}
	
	static public function url($article, $lFull = false) {
		$objectType = self::getObjectType($article);
		if ($objectType == 'Product') {
			$subcatSlug = Hash::get($article, 'Subcategory.slug');
			$url = array(
				'controller' => 'Products', 
				'action' => 'view',
				'category' => $article['Category']['slug'],
				'subcategory' => ($subcatSlug) ? $subcatSlug : 'kupit',
				'objectType' => 'Product',
				'slug' => $article['Product']['slug']
			);
		} elseif ($objectType == 'Category') {
			$url = array(
				'controller' => 'Products', 
				'action' => 'index',
				'category' => $article['Category']['slug'],
				'objectType' => 'Product'
			);
		} elseif ($objectType == 'Subcategory') {
			$url = array(
				'controller' => 'Products', 
				'action' => 'index',
				'category' => $article['Category']['slug'],
				'subcategory' => $article['Subcategory']['slug'],
				'objectType' => 'Product'
			);
		} elseif ($objectType == 'RepairArticle') {
			$url = array(
				'controller' => 'Repair',
				'action' => 'index',
				'slug' => $article['RepairArticle']['slug']
			);
		} else {
			$url = array(
				'controller' => 'Articles', 
				'action' => 'view',
				'objectType' => $objectType,
				'slug' => $article[$objectType]['slug']
			);
		}
		return parent::url($url, $lFull);
	}
	
}