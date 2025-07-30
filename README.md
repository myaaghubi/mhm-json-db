# MHM JSON Database

## Usage

### Composer

Include it like:

```json
{
  "name": "myaaghubi/",
  "description": "",
  "type": "project",
  "license": "LICENSE.md",
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
    // select all: array of records or an empty array
    var_dump($this->db->select());

    // select one or null
    $result = $this->db->selectOne(['name' => 'Moham']);

    // insert
    $insertedRecord = $this->db->insert(['name' => 'Moham', 'email' => '...']);

    // delete: returns the number of deleted
    $deleted = $this->db->delete(['id' => $insertedRecord['id']]);

    // delete all
    $deleted = $this->db->delete();
```

### Test

Just run:
`./vendor/bin/phpunit tests`

### Production

Run `composer install --no-dev`
Run `composer dump`
