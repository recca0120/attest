<?php

namespace Recca0120\Attest\Tests;

use Illuminate\Support\Collection;
use Illuminate\Testing\Assert;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Recca0120\Attest\Permission;
use Recca0120\Attest\Role;
use Recca0120\Attest\User;

class EloquentTest extends TestCase
{
    use DatabaseSetup;
    use MockeryPHPUnitIntegration;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupDatabase();
    }

    public function test_user_has_roles()
    {
        $user = User::create([
            'name' => 'foo',
            'email' => 'foo@bar.com',
            'password' => sha1('foo'),
        ]);

        $roles = Collection::make([
            $this->createRole('role_a'),
            $this->createRole('role_b'),
        ]);

        $user->roles()->saveMany($roles);

        Assert::assertArraySubset($roles->toArray(), $user->roles->toArray());
    }

    public function test_role_has_permissions()
    {
        $role = $this->createRole('role_a');

        $permissions = Collection::make([
            $this->createPermission('permission_a'),
            $this->createPermission('permission_b'),
        ]);

        $role->permissions()->saveMany($permissions);

        Assert::assertArraySubset($permissions->toArray(), $role->permissions->toArray());
    }

    public function test_user_has_permissions()
    {
        $user = User::create([
            'name' => 'foo',
            'email' => 'foo@bar.com',
            'password' => sha1('foo'),
        ]);

        $permissions = Collection::make([
            $this->createPermission('permission_a'),
            $this->createPermission('permission_b'),
        ]);

        $user->permissions()->saveMany($permissions);

        Assert::assertArraySubset($permissions->toArray(), $user->permissions->toArray());
    }

    public function test_user_get_all_permission()
    {
        $user = User::create([
            'name' => 'foo',
            'email' => 'foo@bar.com',
            'password' => sha1('foo'),
        ]);

        $permissions = Collection::make([
            $this->createPermission('permission_a'),
            $this->createPermission('permission_b'),
            $this->createPermission('permission_c'),
            $this->createPermission('permission_d'),
        ]);

        $user->permissions()->saveMany($permissions->slice(0, 2));

        $role = $this->createRole('role_a');

        $role->permissions()->saveMany($permissions->slice(2, 2));

        $user->roles()->saveMany([$role]);

        Assert::assertArraySubset($permissions->toArray(), $user->permissions->toArray());
    }

    private function createRole($name)
    {
        return Role::create(['name' => $name, 'slug' => $name]);
    }

    private function createPermission($name)
    {
        return Permission::create(['name' => $name, 'slug' => $name]);
    }
}
