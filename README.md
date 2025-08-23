# MHM JSON Database
[![UnitTest](https://github.com/myaaghubi/mhm-json-db/actions/workflows/php.yml/badge.svg?branch=main)](https://github.com/myaaghubi/mhm-json-db/actions/workflows/php.yml) 
[![PHP Version Tested](https://img.shields.io/badge/PHP-8.4-blue)](https://img.shields.io/badge/PHP-8.4-blue)

## Usage

### Composer

For the composer, have it like:

```shell
composer require myaaghubi/mhm-json-db
```

### Code

```php
    $db = new MHMJsonDB('mydb.json', $pathToDB);

    // select all: array of records or an empty array
    var_dump($db->select());

    // select one or null
    $result = $db->selectOne(['name' => 'Moham']);

    // returns the whole record as array
    $insertedRecord = $db->insert(['name' => 'Moham', 'email' => '...']);

    // delete: returns the number of deleted
    $deleted = $db->delete(['id' => $insertedRecord['id']]);

    // delete all
    $deleted = $db->delete();
```

### Test

Just run
`./vendor/bin/phpunit`

## License

This project is licensed under an AGPL-3.0 license.
