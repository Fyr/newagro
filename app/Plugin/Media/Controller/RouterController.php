<?
App::uses('AppController', 'Controller');
class RouterController extends AppController {
	var $name = 'Router';
	var $layout = false;
	var $uses = false;
	var $autoRender = false;
	
	public function beforeFilter() {
	}
	
	public function beforeRender() {
	}

	private function isWatermarkNeeded($type, $id) {
		if ($type == 'product') {
			return true;
		}
		if ($type == 'page') {
			// set watermark for news photos
			App::uses('media', 'Media.Model');
			$this->loadModel('Media.Media');
			$media = $this->Media->findById($id);

			App::uses('Article', 'Article.Model');
			$this->loadModel('Article.Article');
			$articleID = Hash::get($media, 'Media.object_id');
			$news = $this->Article->findById($articleID, array('id', 'object_type'));
			return Hash::get($news, 'Article.object_type') == 'News';
		} 
		return false;
	}

	public function index($type, $id, $size, $filename) {
		App::uses('MediaPath', 'Media.Vendor');
		$this->PHMedia = new MediaPath();
		
		$aFName = $this->PHMedia->getFileInfo($filename);
		$fname = $this->PHMedia->getFileName($type, $id, $size, $filename);

		$hasWatermark = $this->isWatermarkNeeded($type, $id);
		
		if ($hasWatermark) {
			$zone = Configure::read('domain.zone');
			$fname = str_replace('.'.$aFName['ext'], '_'.$zone.'.'.$aFName['ext'], $fname);
			if (TEST_ENV) {
				$zone = 'by';
			}
		}
		
		if (file_exists($fname) && filesize($fname)) {
			header('Content-type: image/'.$aFName['ext']);
			echo file_get_contents($fname);
			exit;
		}
		
		App::uses('Image', 'Media.Vendor');
		$image = new Image();
		
		$aSize = $this->PHMedia->getSizeInfo($size);
		$method = $this->PHMedia->getResizeMethod($size);
		$origImg = $this->PHMedia->getFileName($type, $id, null, $aFName['fname'].'.'.$aFName['orig_ext']);

		if ($method == 'thumb') {
			$thumb = $this->PHMedia->getFileName($type, $id, null, 'thumb.png');
			if (file_exists($thumb)) {
				$origImg = $thumb;
			}
		}
		
		$image->load($origImg);
		if ($aSize) {
			$method = $this->PHMedia->getResizeMethod($size);
			$image->{$method}($aSize['w'], $aSize['h']);
		}
		
		if ($hasWatermark) {
			$logo = new Image();
			$logo->load('./img/logo_wmtr_'.$zone.'.png'); // берем сразу полупрозрачную картинку 300х200

			// ресайзим фотки автоматически, чтобы увеличить скорость загрузки
			// качества 1200x достаточно для просмотра на destop-версии сайта
			if ($image->getSizeX() > 1200 || $image->getSizeY() > 900) {
				$image->resize(1200, null);
			}

			// если лого меньше чем фотка
			if ($logo->getSizeX() < $image->getSizeX() && $logo->getSizeY() < $image->getSizeY()) {

				// накладываем водяные знаки в несколько колонок и строк
				$nX = floor($image->getSizeX() / $logo->getSizeX());
				$nY = floor($image->getSizeY() / $logo->getSizeY());

				$startX = floor(($image->getSizeX() - $nX * $logo->getSizeX()) / 2);
				$startY = floor(($image->getSizeY() - $nY * $logo->getSizeY()) / 2);

				for ($i = 0; $i < $nX; $i++) {
					for ($j = 0; $j < $nY; $j++) {
						imagecopy($image->getImage(), $logo->getImage(),
							$startX + $i * $logo->getSizeX(), $startY + $j * $logo->getSizeY(),
							0, 0, $logo->getSizeX(), $logo->getSizeY()
						);
					}
				}
			} else { // размеры фотки после ресайза меньше чем размеры лого 300x200

				// берем уменьшенный вариант лого 150x100 и накладываем 1 раз
				$logo = new Image();
				$logo->load('./img/logo_wmtr2_'.$zone.'.png');
				if ($logo->getSizeX() < $image->getSizeX() && $logo->getSizeY() < $image->getSizeY()) {
					$x = round(($image->getSizeX()) / 2, 0) - round($logo->getSizeX() / 2, 0);
					$y = round(($image->getSizeY()) / 2, 0) - round($logo->getSizeY() / 2, 0);
					imagecopy($image->getImage(), $logo->getImage(), $x, $y, 0, 0, $logo->getSizeX(), $logo->getSizeY());
				} else { // если и уменьшенное лого больше чем размеры фотки после ресайза (т.е. еще меньше чем 150x100)

					// т.к. есть баг с ресайзом лого (при ресайзе исчезает прозрачность и появляется фон),
					// то ресайзим саму картинку, а потом возвращаем ее в исх. размер
					// при таких мелких размерах качество конечного изображения уже неважно - все и так слишком мелко
					$oldSizeX = $image->getSizeX();
					$image->resize($logo->getSizeX(), null); // по идее все картинки по ширине больше чем по высоте

					$x = round(($image->getSizeX()) / 2, 0) - round($logo->getSizeX() / 2, 0);
					$y = round(($image->getSizeY()) / 2, 0) - round($logo->getSizeY() / 2, 0);
					imagecopy($image->getImage(), $logo->getImage(), $x, $y, 0, 0, $logo->getSizeX(), $logo->getSizeY());

					$image->resize($oldSizeX, null);
				}
			}
		}

		if ($aFName['ext'] == 'jpg') {
			$image->outputJpg($fname);
			$image->outputJpg();
		} elseif ($aFName['ext'] == 'png') {
			$image->outputPng($fname);
			$image->outputPng();
		} else {
			$image->outputGif($fname);
			$image->outputGif();
		}
		exit;
	}
	
}
