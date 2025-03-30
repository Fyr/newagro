<?
    $this->Html->css(array('grid', '/Icons/css/icons'), array('inline' => false));
    $this->Html->script(array('cart', 'number_format', 'vendor/jquery/jquery.cookie'), array('inline' => false));

    echo $this->element('title', array('title' => __('My orders')));
?>
<?
    if ($aOrders) {
?>

	<table class="grid" width="100%" cellpadding="0" cellspacing="0">
		<thead>
            <tr>
                <th>N заказа</th>
                <th>Дата</th>
                <th>На адрес</th>
                <th>Комментарий</th>
                <th></th>
            </tr>
		</thead>
		<tbody>
<?
    $class = '';
    foreach($aOrders as $order) {
        $order = $order['SiteOrder'];
        $class = ($class == 'odd') ? 'even' : 'odd';
        $viewURL = $this->Html->url(array('controller' => 'user', 'action' => 'orderview', $order['id']));
?>
            <tr class="gridRow <?=$class?>">
                <td><?=$order['id']?></td>
                <td><?=$this->PHTime->niceShort($order['created'])?></td>
                <td><?=$order['address']?></td>
                <td><?=$order['comment']?></td>
                <td><a class="icon-color icon-preview" href="<?=$viewURL?>" title="<?=__('View Order')?>"></a></td>
            </tr>
<?
    }
?>
        </tbody>
    </table>
    <br/>
    <?=$this->element('paginate')?>
<?
    } else {
?>
    <p>
        У вас нет заказов.<br />
        <br />
        <?=$this->Html->link(__('Back to catalog'), array('controller' => 'products', 'action' => 'index'))?>
    </p>
<?
    }
?>
<style>
.grid > tbody > tr > td {
    padding: 6px 10px;
}
.grid > tbody > tr > td > a {
    top: 0;
}
.icon-color[class^="icon"] {
    margin-right: 0;
}
</style>
