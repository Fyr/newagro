<?=$this->element('title', array('title' => __('Contacts')))?>
<div class="block main clearfix">
	<p>
		<b><?=__('Thank you for your message!')?></b><br />
		<?=__('Click %s to send another message.', $this->Html->link(__('here'), '/contacts/'))?><br />
		<br />
		<a href="/"><?=__('Back to home page')?></a>
	</p>
</div>