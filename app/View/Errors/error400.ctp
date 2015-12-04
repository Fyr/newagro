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
	<p>Извините, по вашему запросу ничего не найдено либо запрашиваемая вами страница не существует.<br />
		Воспользуйтесь навигацией или поиском, чтобы найти необходимую вам информацию.<br />
		<br />
		<a href="/">На главную</a>
	</p>
</div>