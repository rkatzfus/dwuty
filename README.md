datatableswebutility/dwuty

[![Packagist Version](https://img.shields.io/packagist/v/datatableswebutility/dwuty?label=version)](https://packagist.org/packages/datatableswebutility/dwuty)
[![Packagist Downloads](https://img.shields.io/packagist/dt/datatableswebutility/dwuty)](https://packagist.org/packages/datatableswebutility/dwuty)
[![GitHub License](https://img.shields.io/github/license/rkatzfus/dwuty)](https://github.com/rkatzfus/dwuty/blob/main/LICENSE)
[![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/datatableswebutility/dwuty)](https://packagist.org/packages/datatableswebutility/dwuty)  
![MicrosoftSQLServer](https://img.shields.io/badge/Microsoft%20SQL%20Server-CC2927)
![MySQL](https://img.shields.io/badge/mysql-%2300f)
![Postgres](https://img.shields.io/badge/postgres-%23316192)  
![Bootstrap](https://img.shields.io/badge/bootstrap-%238511FA)
![jQuery](https://img.shields.io/badge/jquery-%230769AD)

**dwuty** provides a simple and easy way to generate editable tables on your website.  
The data can be stored in 3 different types of databases via the php [pdo interface](https://www.php.net/manual/en/book.pdo.php).

## Installation

It's important to add `"minimum-stability": "dev"` and `"prefer-stable": true` in your composer.json

> composer.json

```json
{
  "minimum-stability": "dev",
  "prefer-stable": true
}
```

Install the package through [composer](http://getcomposer.org):

```
composer require datatableswebutility/dwuty
```

Make sure, that you include the composer [autoloader](https://getcomposer.org/doc/01-basic-usage.md#autoloading)
somewhere in your codebase.

create `.env` file for the environment and update the database credentials

> .env

```r
API_KEY="*******"

HOST_ENV="*******"
DATABASE_ENV="*******"
USER_ENV="*******"
PASSWORD_ENV="*******"
```

setup `.htaccess` to hide the `.env`

> .htaccess

```r
# Disable index view

Options -Indexes

# Hide a specific file(s)

<Files ~ "\.(env)$">
Order allow,deny
Deny from all
</Files>
```

## Example (mysql)

```php
<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/vendor/autoload.php";
Dotenv\Dotenv::createImmutable($_SERVER['DOCUMENT_ROOT'])->load();

use App\webutility;

$config_webutility = array(
    "debug" => array(
        "database_tools" => false
        , "webutility_ssp" => false
        , "tools" => false
    )
    , "database" => array(
        "type" => "mysql"
        , "credentials" => array(
            "host" => "HOST_ENV"
            , "database" => "DATABASE_ENV"
            , "user" => "USER_ENV"
            , "pass" => "PASSWORD_ENV"
        )
    )
    , "crud" => array(
        "create" => array(
            "activ" => true
        )
        , "update" => array(
            "activ" => true
        )
        , "delete" => array(
            "activ" => true
        )
    )
    , "datasource" => "root_table root"
    , "primarykey" => "root.ID"
    , "lang_iso_639_1" => "en"
);
$obj_webutility = new webutility($config_webutility);
$obj_webutility->new_column("root.TEXT_FIELD", "column: TEXT", EDIT, TEXT);
?>
<link rel="stylesheet" type="text/css" href="/vendor/twbs/bootstrap/dist/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="/vendor/datatables.net/datatables.net-bs5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" type="text/css" href="/vendor/datatables.net/datatables.net-fixedheader-bs5/css/fixedHeader.bootstrap5.min.css" />
<link rel="stylesheet" type="text/css" href="/vendor/select2/select2/dist/css/select2.min.css" />
<script src="/vendor/components/jquery/jquery.min.js"></script>
<div class="container-fluid mt-1">
    <?= $obj_webutility->table_header(); ?>
</div>
<?php
$ary_config = array(
    "default_order" => array(
        "column_no" => 0
        , "direction" => "asc"
    )
    , "datatables_ext" => array(
        "fixedHeader" => "true"
    )
);
$obj_webutility->config($ary_config);
?>
```

## Links

You can find more details and examples on [datatableswebutility.net](https://datatableswebutility.net/docs.php) & checkout the [live - demo](https://datatableswebutility.net/usecase.php)
