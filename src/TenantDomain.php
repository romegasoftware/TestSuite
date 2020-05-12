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
		$this->tenant = factory(config('multitenancy.tenant_model'))->create([
			'domain'=> 'test',
			'name'	=> 'Test'
		]);
		$this->tenant->users()->save($this->actingAsUser());

		$url = Request::getScheme().'://'.$this->tenant->domain.'.'.Request::getHost();
		Config::set('app.url', $url);
        URL::forceRootUrl($url);
	}
}
