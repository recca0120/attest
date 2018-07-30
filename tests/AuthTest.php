<?php

namespace Recca0120\Attest\Tests;

use Recca0120\Attest\Role;
use Recca0120\Attest\User;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Collection;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

class AuthTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    protected function setUp()
    {
        parent::setUp();

        $this->roles = Collection::make([
            new Role(['title' => 'administrator', 'name' => 'administrator']),
            new Role(['title' => 'user', 'name' => 'user']),
            new Role(['title' => 'guest', 'name' => 'guest']),
        ]);

        $this->user = new User([
            'name' => 'foo',
            'email' => 'foo@bar.com',
            'password' => sha1('foo'),
        ]);

        $this->user->setRelation('roles', $this->roles->take(2));
    }

    /** @test */
    public function test_user_have_role_by_string()
    {
        $this->assertTrue($this->user->hasRole('administrator'));
        $this->assertTrue($this->user->hasRole('user'));
        $this->assertFalse($this->user->hasRole('guest'));
    }

    public function test_user_have_role_by_role()
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

    public function test_user_only_require_one_roles()
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
}
