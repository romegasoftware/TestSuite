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


```php
	use TenantDomain;

    public function setUp()
    {
        parent::setUp();
        $this->user->assignRole('basic user');
    }
```

```php
	use TenantAdminDomain;

    public function setUp()
    {
        parent::setUp();
    }
```