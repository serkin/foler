#Web based single page translation system

[![Build Status](https://img.shields.io/travis/serkin/foler.svg?style=flat-square)](https://travis-ci.org/serkin/parser)
[![Coverage Status](https://img.shields.io/coveralls/serkin/foler/master.svg?style=flat-square)](https://coveralls.io/r/serkin/foler?branch=master)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/serkin/foler.svg?style=flat-square)](https://scrutinizer-ci.com/g/serkin/foler/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/serkin/foler/v/stable)](https://packagist.org/packages/serkin/foler)
[![Total Downloads](https://poser.pugx.org/serkin/foler/downloads)](https://packagist.org/packages/serkin/foler)
[![Latest Unstable Version](https://poser.pugx.org/serkin/foler/v/unstable)](https://packagist.org/packages/serkin/foler)
[![License](https://poser.pugx.org/serkin/foler/license)](https://packagist.org/packages/serkin/foler)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/e0c18107-1776-4687-b88c-2094889ae26f/small.png)](https://insight.sensiolabs.com/projects/e0c18107-1776-4687-b88c-2094889ae26f)

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
* Add export to xliff

## Licence
* MIT
