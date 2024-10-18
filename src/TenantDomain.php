<?php

namespace RomegaDigital\TestSuite;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;

trait TenantDomain
{
	use ActingAsUser;

	protected $tenant = [];

	public function tenantDomain()
	{
        	$this->tenant = config('multitenancy.tenant_model')::factory()->create();

		$this->tenant->users()->save($this->actingAsUser());

		$url = Request::getScheme().'://'.$this->tenant->domain.'.'.Request::getHost();
		Config::set('app.url', $url);
        	URL::forceRootUrl($url);
	}
}
