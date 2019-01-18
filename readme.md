## About TestSuite


composer.json

```json
"repositories": {
    "testsuite": {
        "type": "vcs",
        "url": "https://gitlab.com/romegadigitaltools/testsuite.git"
    }
}
```

```sh
composer require romegadigital/testsuite --dev
```

---

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