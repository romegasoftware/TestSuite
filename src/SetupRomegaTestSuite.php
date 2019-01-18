<?php

namespace RomegaDigital\TestSuite;

trait SetupRomegaTestSuite
{
	public function setupRomegaTestSuite($uses)
	{
        //CustomerDomain uses ActingAsUser so we only need one
		if (isset($uses[\RomegaDigital\TestSuite\TenantDomain::class])) {
		    $this->tenantDomain();
		} elseif (isset($uses[\RomegaDigital\TestSuite\TenantAdminDomain::class])) {
		    $this->tenantAdminDomain();
		}  elseif (isset($uses[\RomegaDigital\TestSuite\ActingAsUser::class])) {
		    $this->actingAsUser();
		} 
	}

}
