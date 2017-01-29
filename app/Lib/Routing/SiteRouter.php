<?
App::uses('Router', 'Cake/Routing');
class SiteRouter extends Router {

	static public function getObjectType($article) {
		list($objectType) = array_keys($article);
		return $objectType;
	}

	static public function fullUrl($subdomain, $url) {
		if (!$subdomain) {
			$subdomain = Configure::read('domain.url');
		} elseif (strpos($subdomain, '.') === false) {
			$subdomain.= '.'.Configure::read('domain.url');
		}
		return 'http://'.$subdomain.parent::url($url, false);
	}
	
	static public function url($article, $lFull = false) {
		$objectType = self::getObjectType($article);
		$subdomain = '';
		if ($objectType == 'Product') {
			$subcatSlug = Hash::get($article, 'Subcategory.slug');
			$url = array(
				'controller' => 'Products', 
				'action' => 'view',
				// 'category' => $article['Category']['slug'],
				'subcategory' => ($subcatSlug) ? $subcatSlug : 'kupit',
				'objectType' => 'Product',
				'slug' => $article['Product']['slug']
			);
			if (Configure::read('domain.subdomain') == 'www') {
				$url['subdomain'] = 1;
			} else {
				$url['category'] = $article['Category']['slug'];
			}
		} elseif ($objectType == 'Category') {
			$url = array(
				'controller' => 'Products',
				'action' => 'index',
				// 'category' => $article['Category']['slug'],
				'objectType' => 'Product'
			);
			if (Configure::read('domain.subdomain') == 'www') {
				$url['subdomain'] = 1;
			} else {
				$url['category'] = $article['Category']['slug'];
			}
		} elseif ($objectType == 'Subcategory') {
			$url = array(
				'controller' => 'Products', 
				'action' => 'index',
				// 'category' => $article['Category']['slug'],
				'subcategory' => $article['Subcategory']['slug'],
				'objectType' => 'Product'
			);
			if (Configure::read('domain.subdomain') == 'www') {
				$url['subdomain'] = 1;
			} else {
				$url['category'] = $article['Category']['slug'];
			}
		} elseif ($objectType == 'RepairArticle') {
			$url = array(
				'controller' => 'Repair',
				'action' => 'index',
				'slug' => $article['RepairArticle']['slug']
			);
		} elseif ($objectType == 'Page') {
			$url = array(
				'controller' => 'page',
				'action' => 'show',
				$article['Page']['slug']
			);
		} else {
			$url = array(
				'controller' => 'Articles', 
				'action' => 'view',
				'objectType' => $objectType,
				'slug' => $article[$objectType]['slug']
			);
		}

		if (in_array($objectType, array('Product', 'Category', 'Subcategory')) && Hash::get($article, 'Category.is_subdomain') && Configure::read('domain.subdomain') == 'www') {
			return self::fullUrl($article['Category']['slug'], $url);
		} elseif (Configure::read('domain.category')) {
			return self::fullUrl('', $url);
		}
		return parent::url($url, true);
	}
	
}