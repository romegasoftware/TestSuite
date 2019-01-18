<?php

namespace RomegaDigital\TestSuite;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;

trait TenantDomain
{
    use ActingAsUser;

    protected $tenant = [];

    public function tenantDomain()
    {
        $this->tenant = factory(config('multitenancy.tenant_model'))->create();
        $this->tenant->users()->save($this->actingAsUser());

        $url = Request::getScheme().'://'.$this->tenant->domain.'.'.Request::getHost();
        Config::set('app.url', $url);
    }
}