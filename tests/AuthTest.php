<?php

namespace Recca0120\Attest\Tests;

use Illuminate\Support\Collection;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Recca0120\Attest\Permission;
use Recca0120\Attest\Role;
use Recca0120\Attest\User;

class AuthTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = new User([
            'name' => 'foo',
            'email' => 'foo@bar.com',
            'password' => sha1('foo'),
        ]);

        $this->roles = Collection::make([
            $this->createRole('administrator'),
            $this->createRole('user'),
            $this->createRole('guest'),
        ]);

        $this->user->setRelation('roles', $this->roles->take(2));

        $this->permissions = Collection::make([
            $this->createPermission('read'),
            $this->createPermission('write'),
            $this->createPermission('create'),
        ]);

        $this->user->setRelation('permissions', $this->permissions->take(2));
    }

    /** @test */
    public function test_user_have_role_by_string()
    {
        $this->assertTrue($this->user->hasRole('administrator'));
        $this->assertTrue($this->user->hasRole('user'));
        $this->assertFalse($this->user->hasRole('guest'));
    }

    public function test_user_have_role_by_role_object()
    {
        $this->assertTrue($this->user->hasRole($this->roles[0]));
        $this->assertTrue($this->user->hasRole($this->roles[1]));
        $this->assertFalse($this->user->hasRole($this->roles[2]));
    }

    /** @test */
    public function test_user_must_have_all_roles()
    {
        $this->assertTrue($this->user->hasRole(['administrator', 'user']));
        $this->assertFalse($this->user->hasRole(['administrator', 'guest']));

        $this->assertTrue($this->user->hasRole('administrator', 'user'));
        $this->assertFalse($this->user->hasRole('administrator', 'guest'));

        $this->assertTrue($this->user->hasRole('administrator, user'));
        $this->assertFalse($this->user->hasRole('administrator, guest'));

        $this->assertTrue($this->user->hasRole('administrator,user'));
        $this->assertFalse($this->user->hasRole('administrator,guest'));

        $this->assertTrue($this->user->hasRole('administrator & user'));
        $this->assertFalse($this->user->hasRole('administrator & guest'));

        $this->assertTrue($this->user->hasRole('administrator && user'));
        $this->assertFalse($this->user->hasRole('administrator && guest'));

        $this->assertTrue($this->user->hasRole('administrator and user'));
        $this->assertFalse($this->user->hasRole('administrator and guest'));

        $this->assertTrue($this->user->hasRole('administrator AND user'));
        $this->assertFalse($this->user->hasRole('administrator AND guest'));
    }

    public function test_user_only_require_one_role()
    {
        $this->assertTrue($this->user->hasRole('administrator|user'));
        $this->assertTrue($this->user->hasRole('administrator|guest'));

        $this->assertTrue($this->user->hasRole('administrator | user'));
        $this->assertTrue($this->user->hasRole('administrator | guest'));

        $this->assertTrue($this->user->hasRole('administrator||user'));
        $this->assertTrue($this->user->hasRole('administrator||guest'));

        $this->assertTrue($this->user->hasRole('administrator || user'));
        $this->assertTrue($this->user->hasRole('administrator || guest'));

        $this->assertTrue($this->user->hasRole('administrator or user'));
        $this->assertTrue($this->user->hasRole('administrator or guest'));

        $this->assertTrue($this->user->hasRole('administrator OR user'));
        $this->assertTrue($this->user->hasRole('administrator OR guest'));

        $this->assertFalse($this->user->hasRole('guest'));
    }

    public function test_user_have_permission_by_string()
    {
        $this->assertTrue($this->user->hasPermission('read'));
        $this->assertTrue($this->user->hasPermission('write'));
        $this->assertFalse($this->user->hasPermission('create'));
    }

    public function test_user_have_permission_by_permission_object()
    {
        $this->assertTrue($this->user->hasPermission($this->permissions[0]));
        $this->assertTrue($this->user->hasPermission($this->permissions[1]));
        $this->assertFalse($this->user->hasPermission($this->permissions[2]));
    }

    public function test_user_must_have_all_permissions()
    {
        $this->assertTrue($this->user->hasPermission(['read', 'write']));
        $this->assertFalse($this->user->hasPermission(['read', 'create']));

        $this->assertTrue($this->user->hasPermission('read', 'write'));
        $this->assertFalse($this->user->hasPermission('read', 'create'));

        $this->assertTrue($this->user->hasPermission('read, write'));
        $this->assertFalse($this->user->hasPermission('read, create'));

        $this->assertTrue($this->user->hasPermission('read,write'));
        $this->assertFalse($this->user->hasPermission('read,create'));

        $this->assertTrue($this->user->hasPermission('read & write'));
        $this->assertFalse($this->user->hasPermission('read & create'));

        $this->assertTrue($this->user->hasPermission('read && write'));
        $this->assertFalse($this->user->hasPermission('read && create'));

        $this->assertTrue($this->user->hasPermission('read and write'));
        $this->assertFalse($this->user->hasPermission('read and create'));

        $this->assertTrue($this->user->hasPermission('read AND write'));
        $this->assertFalse($this->user->hasPermission('read AND create'));
    }

    public function test_user_only_require_one_permission()
    {
        $this->assertTrue($this->user->hasPermission('read|write'));
        $this->assertTrue($this->user->hasPermission('read|create'));

        $this->assertTrue($this->user->hasPermission('read | write'));
        $this->assertTrue($this->user->hasPermission('read | create'));

        $this->assertTrue($this->user->hasPermission('read||write'));
        $this->assertTrue($this->user->hasPermission('read||create'));

        $this->assertTrue($this->user->hasPermission('read || write'));
        $this->assertTrue($this->user->hasPermission('read || create'));

        $this->assertTrue($this->user->hasPermission('read or write'));
        $this->assertTrue($this->user->hasPermission('read or create'));

        $this->assertTrue($this->user->hasPermission('read OR write'));
        $this->assertTrue($this->user->hasPermission('read OR create'));

        $this->assertFalse($this->user->hasPermission('create'));
    }

    private function createRole($name)
    {
        return new Role(['name' => $name, 'slug' => $name]);
    }

    private function createPermission($name)
    {
        return new Permission(['name' => $name, 'slug' => $name]);
    }
}
