kohana-notification
===================

Notification module for Kohana.

Session is used to store the notifications and errors. If you would like to use a specific session key, you may set the ```Notification::$session_key``` variable in your ```bootstrap.php``` file.

To fetch errors and notifications, you may either access their array or use the function named the same way.

```php
    $notifications = Notification::instance()->notifications;
    
    $notifications = Notification::instance()->notifications();
```

Using the function will clear the notification array. This is how you consume a notification.

## Usage

Displaying simple message

```php
Notification::instance()->add(Notification::SUCCESS, 'Welcome :username!', array('username' => $user->username));
```

Adding errors

```php
Notification::instance()->errors($orm_validation_exception, 'model', TRUE);
```

Then in your View

```php
foreach(Notification::instance()->notifications as $notification) {
    echo '<div class="alert alert-' . $notification['level'] . '">' . $notification['message'] . '</div>';
}
```

Or using Javascript (they are automatically added)

```php
<script type="application/javascript">
    Notification.notifications = <?php json_encode(Notification::instance()->notifications()) ?>;
    Notification.errors = <?php json_encode(Notification::instance()->errors()) ?>;
</script>

<div id="notifications"></div>
```

You can then add notifications from Javascript

```php
Notification.add(Notification.ERROR, 'Hey! Subscribe first!');
```
