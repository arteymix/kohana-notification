<?php foreach (ValidForm::instance()->notifications() as $notification) : ?>
    <div class="alert alert-<?php echo $notification->type ?>"><?php echo $notification->message ?>
        <button type="button" class="close" data-dismiss="alert">×</button>
    </div>
<?php endforeach; ?>