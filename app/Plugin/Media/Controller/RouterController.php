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
	
	public function index($type, $id, $size, $filename) {
		App::uses('MediaPath', 'Media.Vendor');
		$this->PHMedia = new MediaPath();
		
		$aFName = $this->PHMedia->getFileInfo($filename);
		$fname = $this->PHMedia->getFileName($type, $id, $size, $filename);
		if ($type == 'product') {
			$zone = Configure::read('domain.zone');
			$fname = str_replace('.'.$aFName['ext'], '_'.$zone.'.'.$aFName['ext'], $fname);
			if (TEST_ENV) {
				$zone = 'by';
			}
		}
		
		if (file_exists($fname)) {
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
		
		if ($type == 'product') {

			$logo = new Image();
			$logo->load('./img/logo_'.$zone.'.gif');

			// т.к. есть баг с ресайзом лого (при ресайзе исчезает прозрачность и появляется фон),
			// то ресайзим саму картинку, а потом возвращаем ее в исх. размер или ресайзим в нужный
			$oldSizeX = $image->getSizeX();
			$oldSizeY = $image->getSizeY();
			$lRestoreSize = false;
			if ($aSize) {
				if ($logo->getSizeX() > $image->getSizeX()) {
					$image->{$method}($logo->getSizeX(), null);
					$lRestoreSize = true;
				}
			} elseif ($image->getSizeX() > 400) {
				$logo->load('./img/logo_'.$zone.'.gif'); // './img/logo_big_'.$zone.'.gif'
			}
			$x = round(($image->getSizeX()) / 2, 0) - round($logo->getSizeX() / 2, 0);
			$y = round(($image->getSizeY()) / 2, 0) - round($logo->getSizeY() / 2, 0);
			imagealphablending($image->getImage(), false);
			imagesavealpha($image->getImage(), true);
			imagecopymerge($image->getImage(), $logo->getImage(), $x, $y, 0, 0, $logo->getSizeX(), $logo->getSizeY(), 40);
			if ($lRestoreSize) {
				$image->{$method}($oldSizeX, null);
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
