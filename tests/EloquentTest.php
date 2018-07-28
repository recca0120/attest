<?php

namespace Recca0120\Attest\Tests;

use Recca0120\Attest\Role;
use Recca0120\Attest\User;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Collection;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class EloquentTest extends TestCase
{
    use DatabaseSetup;
    use MockeryPHPUnitIntegration;

    protected function setUp()
    {
        parent::setUp();
        $this->setupDatabase();
        $this->user = User::create([
            'name' => 'foo',
            'email' => 'foo@bar.com',
            'password' => sha1('foo'),
        ]);
    }

    public function test_user_has_roles()
    {
        $roles = Collection::make([
            $this->createRole('role_a'),
            $this->createRole('role_b'),
        ]);

        $this->user->roles()->saveMany($roles);

        $this->assertArraySubset($roles->toArray(), $this->user->roles->toArray());
    }

    private function createRole($name)
    {
        return Role::create(['title' => $name, 'name' => $name]);
    }
}
