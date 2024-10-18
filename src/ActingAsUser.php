<?php

namespace RomegaDigital\TestSuite;

trait ActingAsUser
{
    protected $user = [];

    public function actingAsUser()
    {
        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->registerPermissions();
        app()['cache']->forget('spatie.permission.cache');
        $this->seed('RolesAndPermissionsSeeder');

        $this->user = config('multitenancy.user_model')::factory()->create();

        $this->actingAs($this->user);
        
        return $this->user;
    }
}
