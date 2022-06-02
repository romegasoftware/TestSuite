# Test Suite

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Travis](https://img.shields.io/travis/romegasoftware/testsuite.svg?style=flat-square)]()
[![Total Downloads](https://img.shields.io/packagist/dt/romegasoftware/testsuite.svg?style=flat-square)](https://packagist.org/packages/romegasoftware/testsuite)

## Install
```sh
composer require romegasoftware/testsuite --dev
```

## Usage

```sh
art make:factory TenantFactory
```

```php
// TenantFactory.php
$factory->define(App\Tenant::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'domain' => $faker->word,
    ];
});
```

```php
	use TenantDomain;

    public function setUp()
    {
        parent::setUp();
        $this->user->assignRole('basic user');
    }
```

---

```php
	use TenantAdminDomain;
```