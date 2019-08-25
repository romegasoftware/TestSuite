<?php

namespace RomegaDigital\TestSuite\Fixtures\Traits;

use RomegaDigital\TestSuite\TenantAdminDomain;

trait NovaPolicyTestCases
{
    use TenantAdminDomain;

    public $permissionsClass = \App\Enums\Permissions::class;

    /** @test **/
    public function it_cant_read_resource_without_permission()
    {
        $user = factory(\App\User::class)->create();
        $user->tenants()->save($this->tenantAdmin);
        $resource = factory($this->modelClass)->create($this->remapAttributes());

        $this->expectStatusCode(403)
            ->getResource($resource->id, $user);

        $user->givePermissionTo($this->permissionsClass::read($this->modelClass));

        $this->getResource($resource->id, $user);
    }

    /** @test */
    public function it_cant_create_a_resource_without_permission()
    {
        $user = factory(\App\User::class)->create();
        $user->tenants()->save($this->tenantAdmin);

        $this->expectStatusCode(403)
            ->storeResource([], $user);

        $user->givePermissionTo($this->permissionsClass::create($this->modelClass));

        $this->storeResource([], $user);
    }

    /** @test */
    public function it_cant_update_a_resource_without_permission()
    {
        $user = factory(\App\User::class)->create();
        $user->tenants()->save($this->tenantAdmin);
        $resource = factory($this->modelClass)->create($this->remapAttributes());

        $this->expectStatusCode(403)
            ->updateResource([
                'id' => $resource->id,
            ], $user);

        $user->givePermissionTo($this->permissionsClass::update($this->modelClass));

        $this->updateResource([
                'id' => $resource->id,
            ], $user);
    }

    /** @test */
    public function it_cant_destroy_a_resource_without_permission()
    {
        $user = factory(\App\User::class)->create();
        $user->tenants()->save($this->tenantAdmin);
        $resource = factory($this->modelClass)->create($this->remapAttributes());

        $this->deleteResource([$resource->id], $user);
        if(in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($this->modelClass))){
            $this->assertEquals($resource->fresh()->{$resource->getDeletedAtColumn()}, null);
        } else {
            $this->assertDatabaseHas($resource->getTable(), $resource->only('id'));
        }

        $user->givePermissionTo($this->permissionsClass::delete($this->modelClass));
        $this->deleteResource([$resource->id], $user);
        if(in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($this->modelClass))){
            $this->assertSoftDeleted($resource->getTable(), $resource->only('id'));
        } else {
            $this->assertDatabaseMissing($resource->getTable(), $resource->only('id'));
        }
    }

    /**
     * Force attributes required for testing.
     *
     * @return array
     */
    protected function remapAttributes()
    {
        return [];
    }
}
