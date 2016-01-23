<?php
/**
?>
<h2>!<?php echo $name; ?></h2>
<p class="error">
	<strong><?php echo __d('cake', 'Error'); ?>: </strong>
	<?php printf(
		__d('cake', 'The requested address %s was not found on this server.'),
		"<strong>'{$url}'</strong>"
	); ?>
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
	<p><?=__('Sorry, no items found or page does not exists.')?><br />
		<?=__('Please use the navigation bar or the search to find the information you need.')?><br />
		<br />
		<a href="/"><?=__('Back to home page')?></a>
	</p>
</div>