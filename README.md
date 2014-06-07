kohana-notification
===================

Notification module for Kohana.

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
