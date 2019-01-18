<?php

namespace RomegaDigital\TestSuite;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;

trait TenantAdminDomain
{
    use ActingAsUser;

    protected $tenant = [];

    public function tenantAdminDomain()
    {
        $this->seed('TenantTableSeeder');

        $this->tenant = config('multitenancy.tenant_model')::find(1);
        $this->tenant->users()->save($this->actingAsUser());
        $this->user->assignRole('Super Administrator');

        $url = Request::getScheme().'://'.$this->tenant->domain.'.'.Request::getHost();
        Config::set('app.url', $url);
    }
}
