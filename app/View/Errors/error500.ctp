<?php
/**

?>
<h2>#<?php echo $name; ?></h2>
<p class="error">
	<strong><?php echo __d('cake', 'Error'); ?>: </strong>
	<?php echo __d('cake', 'An Internal Error Has Occurred.'); ?>
</p>
<?php
if (Configure::read('debug') > 0):
	echo $this->element('exception_stack_trace');
endif;
 */
?>
<?
echo $this->element('title', array('title' => __('Page not found')));
?>
<div class="block main clearfix">
	<p><b><?=__('Attention!')?></b> <?=__('Server error occurred by your request.')?><br />
		<?=__('Our experts are fixing it right now. It will be fixed as soon as possible.')?><br />
		<br />
		<a href="/"><?=__('Back to home page')?></a>
	</p>
</div>