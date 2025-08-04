# MHM JSON Database
[![Composer Testing](https://github.com/myaaghubi/mhm-json-db/actions/workflows/php.yml/badge.svg?branch=main)](https://github.com/myaaghubi/mhm-json-db/actions/workflows/php.yml) 
[![PHP Version Tested](https://img.shields.io/badge/PHP-8.4-blue)](https://img.shields.io/badge/PHP-8.4-blue)

## Usage

### Composer

Add `composer.json` like:

```json
{
  "name": "myaaghubi/",
  "description": "",
  "type": "project",
  "license": "proprietary",
  "authors": [
    {
      "name": "Mohammad Yaaghubi",
      "email": "m.yaaghubi.abc@gmail.com"
    }
  ],
  "require": {
    "php": ">=8.1"
  },
  "require-dev": {
    "phpunit/phpunit": "^11"
  },
  "autoload": {
    "psr-4": {}
  },
  "autoload-dev": {
    "psr-4": {}
  },
  "repositories": [
    {
      "type": "vcs",
      "url": "https://github.com/myaaghubi/MHMJsonDB"
    }
  ],
  "require": {
    "myaaghubi/MHMJsonDB": "dev-main"
  }
}
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

### Production

Run `composer install --no-dev`
Run `composer dump`

## License

This project is licensed under a proprietary license. All rights reserved. Unauthorized copying, distribution, or modification of this project is strictly prohibited.
