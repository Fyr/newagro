<?
    $message = $this->Session->flash('info');
    if ($message) {
?>
<div class="alert alert-success">
    <button type="button" class="close" data-dismiss="alert" onclick="$('.alert').fadeOut()">×</button>
    <div id="successMessage" class="message"><?=$message?></div>
</div>
<?
    }
?>
