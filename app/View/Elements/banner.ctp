<span class="bannerSlot">
<?
	$margin = ($banner['Banner']['slot'] == 3 || $banner['Banner']['slot'] == 5) ? 'margin: 0 auto 20px auto' : 'margin: 10px auto 20px auto';
	if ($banner['Banner']['type'] == BannerType::HTML) {
		echo $banner['Banner']['options']['html'];
	} elseif ($banner['Banner']['type'] == BannerType::IMAGE) { 
		$w = min($min_w, $banner['Media'][0]['orig_w']);
		$h = floor($banner['Media'][0]['orig_h'] * $w / $banner['Media'][0]['orig_w']);
		$media = $banner['Media'][0];
		$src = $this->Media->MediaPath->getImageUrl($media['object_type'], $media['id'], ($media['orig_w'] > $min_w) ? $min_w.'x' : 'noresize', $media['file'].$media['ext']);
		$url = Hash::get($banner, 'Banner.options.url_img');
		$stretch = Hash::get($banner, 'Banner.options.stretch');
		if ($stretch) {
			$a_style = "{$margin}; display: block;";
			$img_style = 'width: 100%';
		} else {
			$a_style = "width: {$w}px; height: {$h}px; {$margin}; display: block;";
			$img_style = '';
		}
?>
<a href="<?=($url) ? $url : 'javascript:void(0)'?>" style="<?=$a_style?>">
	<?=$this->Html->image($src, array('alt' => Hash::get($banner, 'Banner.options.alt'), 'style' => $img_style))?>
</a>
<?
	} elseif ($banner['Banner']['type'] == BannerType::SLIDER) {
		$w = min($min_w, $banner['Media'][0]['orig_w']);
		$h = floor($banner['Media'][0]['orig_h'] * $w / $banner['Media'][0]['orig_w']);
		$style = "width: {$w}px; height: {$h}px; {$margin}";
		$url = Hash::get($banner, 'Banner.options.url');
?>
<a href="<?=($url) ? $url : 'javascript:void(0)'?>">
<div id="banner<?=$banner['Banner']['id']?>" class="nivoSlider" style="<?=$style?>">
<?
	$options = array();
	foreach($banner['Media'] as $media) {
		$src = $this->Media->MediaPath->getImageUrl($media['object_type'], $media['id'], ($media['orig_w'] > $min_w) ? $min_w.'x' : 'noresize', $media['file'].$media['ext']);
		echo $this->Html->image($src, $options);
		$options = array('style' => 'display: none;');
	}
?>
</div>
</a>
<?
	}
?>
</span>
