#Web based single page translation system

To get started you need:
* Created database and user in your MySQL server 
* Import db schema from `dump.sql` to your db
* Copy `foler.php` to your server. You can rename it if you want
* Adjust db settings in `foler.php`

## Adjusting settings
Open `foler.php` and change your db credentials
```php
$app['config'] = array(
    'db' => array(
        'dsn'      => 'mysql:dbname=foler;host=localhost',
        'user'      => 'foler',
        'password'  => '*********'
    ),
    'url' => $_SERVER['PHP_SELF'],
    'debug' => false
);
```
## Screenshots
![Foler](screenshot.png?raw=true "Foler")


## TODO
* Add Russian localization
* Add German localization

## Licence
* MIT