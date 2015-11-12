<?	
	echo $this->PHForm->input('status', array('label' => false, 'multiple' => 'checkbox', 'options' => array('active' => __('Active')), 'class' => 'checkbox inline'));
	echo $this->PHForm->input('title');
	echo $this->PHForm->input('slot', array('options' => $slotPlaces));
	echo $this->PHForm->input('type', array('options' => $bannerTypes));
	echo $this->PHForm->input('Banner.options.html', array(
		'type' => 'textarea', 
		'label' => array('class' => 'control-label', 'text' => __('HTML-code')),
		'div' => array('class' => 'control-group bannerOptions bannerType-'.BannerType::HTML)
	));
	echo $this->PHForm->input('Banner.options.url_img', array(
		'label' => array('class' => 'control-label', 'text' => __('URL')),
		'div' => array('class' => 'control-group bannerOptions bannerType-'.BannerType::IMAGE)
	));
	echo $this->PHForm->input('Banner.options.alt', array(
		'label' => array('class' => 'control-label', 'text' => __('Alt.title')),
		'div' => array('class' => 'control-group bannerOptions bannerType-'.BannerType::IMAGE)
	));
	echo $this->PHForm->input('Banner.options.url', array(
		'label' => array('class' => 'control-label', 'text' => __('URL')),
		'div' => array('class' => 'control-group bannerOptions bannerType-'.BannerType::SLIDER)
	));
	echo $this->PHForm->input('sorting', array('class' => 'input-small'));
?>
<script type="text/javascript">
function bannerTypeUpdate() {
	$('.bannerOptions').hide();
	$('.bannerType-' + $('#BannerType').val()).show();
}

$(document).ready(function(){
	bannerTypeUpdate();
	
	$('#BannerType').change(function(){
		bannerTypeUpdate();
	});
});

</script>