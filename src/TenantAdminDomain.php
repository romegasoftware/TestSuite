<?php

namespace RomegaDigital\TestSuite;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

trait TenantAdminDomain
{
    use ActingAsUser;

    protected $tenantAdmin = [];

    public function tenantAdminDomain()
    {
        $this->seed('TenantTableSeeder');

        $this->tenantAdmin = config('multitenancy.tenant_model')::find(1);
        $this->tenantAdmin->users()->save($this->actingAsUser());
        $this->user->assignRole(config('multitenancy.roles.super_admin'));

        $url = Request::getScheme().'://'.$this->tenantAdmin->domain.'.'.Request::getHost();
        Config::set('app.url', $url);
        URL::forceRootUrl($url);
    }
}
