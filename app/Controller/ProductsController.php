<?php
App::uses('AppController', 'Controller');
App::uses('AppModel', 'Model');
App::uses('Media', 'Media.Model');
App::uses('PMFormData', 'Form.Model');
App::uses('Seo', 'Seo.Model');
App::uses('Product', 'Model');
App::uses('Category', 'Model');
App::uses('Subcategory', 'Model');
App::uses('SiteRouter', 'Lib/Routing');
App::uses('PHTimeHelper', 'Core.View/Helper');

class ProductsController extends AppController {
	public $name = 'Products';
	public $uses = array('Category', 'Subcategory', 'Product', 'Seo.Seo');
	public $components = array('Recaptcha.Recaptcha');
	// public $helpers = array('Media.PHMedia', 'Core.PHTime', 'Recaptcha.Recaptcha');
	
	const PER_PAGE = 51;

	public function index($catSlug = '', $subcatSlug = '') {
		$this->paginate = array(
			'conditions' => array('Product.published' => 1),
			'limit' => self::PER_PAGE, 
			'page' => $this->request->param('page'),
			// 'order' => 'Product.created DESC'
		);
		$category = array();
		if ($catSlug) {
			$this->request->data('Category.slug', $catSlug);
			$category = $this->Category->findBySlug($catSlug);
			if ($category) {
				$this->set('category', $category);
				$page_title = $category['Category']['title'];
				$this->seo = $this->Seo->defaultSeo($category['Seo'],
					'Каталог продукции '.$page_title,
					"каталог продукции {$page_title}, запчасти для тракторов {$page_title}, запчасти для спецтехники {$page_title}, запчасти для {$page_title}",
					"На нашем сайте вы можете приобрести лучшие запчасти {$page_title} в ".((Configure::read('domain.zone') == 'ru') ? 'России' : 'Белоруссии').". Низкие цены на спецтехнику, быстрая доставка по стране, диагностика, ремонт."
				);
			}
		}

		$subcategory = array();
		if ($subcatSlug) {
			$this->request->data('Subcategory.slug', $subcatSlug);
			$subcategory = $this->Subcategory->findBySlug($subcatSlug);
			if ($subcategory) {
				$this->set('subcategory', $subcategory);
				$this->set('currSubcat', $subcategory['Subcategory']['id']);
				$page_title = $subcategory['Subcategory']['title'];
				$this->seo = $this->Seo->defaultSeo($subcategory['Seo'],
					'Каталог продукции ' . $page_title,
					"каталог продукции {$page_title}, запчасти для тракторов {$page_title}, запчасти для спецтехники {$page_title}, запчасти для {$page_title}",
					"На нашем сайте вы можете приобрести лучшие запчасти {$page_title} в ".((Configure::read('domain.zone') == 'ru') ? 'России' : 'Белоруссии').". Низкие цены на спецтехнику, быстрая доставка по стране, диагностика, ремонт."
				);
			}
		}
		if (($catSlug && !$category) || ($subcatSlug && !$subcategory)) {
			$slug = ($subcatSlug) ? $subcatSlug : $catSlug;
			$product = $this->Product->findBySlugAndPublished($slug, 1);
			if ($product) {
				return $this->redirect(SiteRouter::url($product));
			} else {
				return $this->redirect404();
			}
		}

		if ($q = $this->request->query('q')) {
			$this->logSearch($q);
			$this->processFilter($q);
			$this->set('directSearch', true);
		}
		if ($data = $this->request->data) {
			$this->paginate['conditions'] = array_merge($this->paginate['conditions'], $this->postConditions($data));
		}
		$aProducts = $this->paginate('Product');
		$this->set('aArticles', $aProducts);
		$this->set('objectType', 'Product');
	}
	
	public function view($slug) {
		$article = $this->Product->findBySlug($slug);
		if (!$article) {
			return $this->redirect404();
		}
		$id = $article['Product']['id'];
		
		$this->loadModel('MediaArticle');
		$aMedia = $this->MediaArticle->getObjectList('Product', $id); // $this->Media->getList(array('object_type' => 'Product', 'object_id' => $id));
		$article['Media'] = Hash::extract($aMedia, '{n}.MediaArticle');
		
		$this->loadModel('Form.PMFormData');
		$formData = $this->PMFormData->getObject('ProductParam', $id);
		$article['PMFormData'] = Hash::extract($formData, 'PMFormData');
		
		$this->loadModel('Form.PMFormField');
		$conditions = array('PMFormField.object_type' => 'SubcategoryParam', 'exported' => 1);
		$order = 'PMFormField.sort_order ASC';
		$fields = $this->PMFormField->find('all', compact('conditions', 'order'));
		$article['PMFormField'] = Hash::extract($fields, '{n}.PMFormField');
		
		$this->set('article', $article);
		
		$this->seo = $this->Seo->defaultSeo($article['Seo'],
			$article['Product']['title_rus'],
			$article['Product']['title_rus'].", ".str_replace(',', ' ', $article['Product']['title_rus'])." ".$article['Category']['title'].", запчасти для спецтехники ".$article['Category']['title'].", запчасти для ".$article['Category']['title'],
			'На нашем сайте вы можете приобрести '.str_replace(',', ' ', $article['Product']['title_rus']).' для трактора или спецтехники '.$article['Category']['title']." в ".((Configure::read('domain.zone') == 'ru') ? 'России' : 'Белоруссии').". Низкие цены на спецтехнику, быстрая доставка по стране, диагностика, ремонт."
		);
		unset($this->seo['keywords']);
		unset($this->seo['descr']);
	}

	private function logSearch($q) {
		$this->loadModel('SearchLog');
		$this->SearchLog->save(array('q' => $q, 'ip' => $_SERVER['REMOTE_ADDR']));
	}

	private function processFilter($value) {
		// очищаем от лишних пробелов
		$_value = $this->stripSpaces(mb_strtolower($value));

		// если ввели только номер - поиск по номерам
		$aWords = explode(' ', $_value);
		if (count($aWords) == 1 && $this->isDigitWord($value)) {
			$this->processNumber($value);
			return;
		}

		$aWords = $this->processTextRequest($_value);
		$this->paginate['conditions']['Search.body LIKE '] = '%'.implode('%', $aWords).'%';

		//$this->paginate['fields'][] = "MATCH (Search.body) AGAINST ('{$_value}' IN BOOLEAN MODE) AS rel";
		//$this->paginate['conditions'][] = "MATCH (Search.body) AGAINST ('{$_value}' IN BOOLEAN MODE)";
		//$this->paginate['order'] = 'Product.created DESC';
		//$this->paginate['order'] = array("MATCH (Search.body) AGAINST ('{$_value}' IN BOOLEAN MODE) DESC");
		//$this->paginate['order'] = 'rel DESC';
	}

	private function processTextRequest($_value) {
		// вырезаем из оригинального запроса найденные слова категорий
		list($_value, $aExact) = $this->getExactWords($_value);
		$aWords = explode(' ',$_value);

		// Разделяем номера деталей и текст
		$aDigiWords = array();
		$aRest = array();
		foreach($aWords as $word) {
			if ($this->isDigitWord($word)) {
				$aDigiWords[] = $this->stripWord($word);
			} else {
				$aRest[] = $this->stripWord($word);
			}
		}
		unset($word);

		$aRest = $this->stripShortWords($aRest); // убиваем в тексте короткие незначащие слова
		$aRest = $this->stripStopWords($aRest); // убиваем в тексте стоп-слова
		$aWords = array_merge($aDigiWords, $aRest, $aExact);
		return $aWords;
	}

	private function getExactWords($q) {
		$_q = array();

		$this->loadModel('VcarsArticle');
		$fields = array('id', 'object_type', 'title', 'LENGTH(title) AS len');
		$conditions = array('object_type' => array('Subcategory', 'Category', 'Brand'));
		$order = 'len DESC, title ASC';
		$aArticles = $this->VcarsArticle->find('all', compact('fields', 'conditions', 'order'));

		$_factor = array();
		$_words = explode(' ', $q);
		foreach($aArticles as $article) {
			$id = $article['VcarsArticle']['id'];
			$objectType = $article['VcarsArticle']['object_type'];
			$title = mb_strtolower($article['VcarsArticle']['title']);
			$title = str_replace(array('.', '-', ',', '/', '\\', '&'), ' ', $title);
			$title = $this->stripSpaces($title);

			if ($info = $this->getWordsInfo($_words, $title)) {
				$total = count($info);
				$_factor[$id] = compact('total', 'info', 'id', 'objectType', 'title');
			}
		}

		if ($_factor) {
			// если точные совпадения по категориям найдены - вырезаем их
			$_factor = Hash::sort($_factor, '{n}.total', 'desc');
			foreach($_factor[0]['info'] as $_word) {
				$q = str_replace($_word, '', $q);
			}
			$_q = $_factor[0]['info'];
		}

		return array($this->stripSpaces($q), $_q);
	}

	private function getWordsInfo($_words, $title) {
		$_match = array();
		foreach(explode(' ', $title) as $i => $_title) {
			foreach($_words as $_word) {
				if ($_word == $_title) {
					$_match[$i] = $_word;
				}
			}
		}
		return $_match;
	}

	private function stripWord($q) {
		return str_replace(array('.', '', '-', ',', '/', '\\'), '', $q);
	}

	private function stripSpaces($q) {
		return trim(str_replace(array('    ', '   ', '  '), ' ', $q));
	}

	private function stripShortWords($aWords) {
		$_words = array();
		foreach($aWords as $word) {
			if ($this->isRu($word) && mb_strlen($word) <= 2) {
				// исключаем такие слова
			} else {
				$_words[] = $word;
			}
		}
		return $_words;
	}


	private function stripStopWords($aWords) {
		$aStopWords = explode(' ', Configure::read('search.stopWords'));
		$_words = array();
		foreach($aWords as $word) {
			if (in_array($word, $aStopWords)) {
				// исключаем такие слова
			} else {
				$_words[] = $word;
			}
		}
		return $_words;
	}
	/**
	 * Возвращает true, если слово состоит из русских букв
	 * (Считаем, что состоит, если хотя бы один символ русский, т.к. могут быть опечатки)
	 * @param $q
	 * @return bool
	 */
	private function isRu($q) {
		for($i = 0; $i < mb_strlen($q); $i++) {
			$ch = mb_substr($q, $i, 1);
			if (mb_strpos('абвгдеёжзийклмнопрстуфхцчшщъыьэюя', $ch) !== false) {
				return true;
			}
		}
		return false;
	}

	private function isDigitWord($q) {
		for($i = 0; $i < mb_strlen($q); $i++) {
			$ch = mb_substr($q, $i, 1);
			if (!preg_match('/[a-z0-9\-\.\\/]/', $ch)) {
				return false;
			}
		}
		return preg_match('/.*[0-9]+.*/', $q) && true;
	}

	private function processNumber($value) {
		$_value = str_replace(array('.', '-', ',', '/', '\\'), '', $value);
		$this->loadModel('DetailNum');
		$product_ids = $this->DetailNum->findDetails($this->DetailNum->stripList($value));
		$this->paginate['conditions'][] = array('Product.id' => $product_ids);
		$order = array();
		foreach ($product_ids as $id) {
			$order[] = 'Product.id = ' . $id . ' DESC';
		}
		$this->paginate['order'] = implode(', ', $order);
	}

	public function runTests() {
		$this->autoRender = false;
		assertTrue('isDigitWord Test 1', $this->isDigitWord('bf1234'));
		assertTrue('isDigitWord Test 2', $this->isDigitWord('1234566bf'));
		assertTrue('isDigitWord Test 3', $this->isDigitWord('123bf123'));
		assertTrue('isDigitWord Test 5', $this->isDigitWord('0'));
		assertTrue('isDigitWord Test 6', $this->isDigitWord('0123'));
		assertTrue('isDigitWord Test 7', !$this->isDigitWord('a'));
		assertTrue('isDigitWord Test 8', $this->isDigitWord('a/123.21-bf'));
		assertTrue('isDigitWord Test 9', !$this->isDigitWord('г/123.21-bf'));
		assertTrue('isDigitWord Test 10', !$this->isDigitWord('mercedes-benz'));

		$q = 'diesel camshaft fp';
		$sample = 'camshaft fp diesel';
		assertEqual('processTextRequest: '.$q, $sample, implode(' ', $this->processTextRequest($q)));

		$q = 'deutz прокладка гбц 1013';
		$sample = 'прокладка гбц deutz 1013';
		assertEqual('processTextRequest: '.$q, $sample, implode(' ', $this->processTextRequest($q)));

		$q = 'прокладка гбц для мотора 1013 deutz ';
		$sample = 'прокладка гбц deutz 1013';
		assertEqual('processTextRequest: '.$q, $sample, implode(' ', $this->processTextRequest($q)));

	}
}
