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
            new Role(['title' => 'role_a', 'name' => 'role_a']),
            new Role(['title' => 'role_a', 'name' => 'role_b']),
            new Role(['title' => 'role_a', 'name' => 'role_c']),
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
        $this->assertTrue($this->user->hasRole('role_a'));
        $this->assertTrue($this->user->hasRole('role_b'));
        $this->assertFalse($this->user->hasRole('role_c'));
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
        $this->assertTrue($this->user->hasRole(['role_a', 'role_b']));
        $this->assertFalse($this->user->hasRole(['role_a', 'role_c']));

        $this->assertTrue($this->user->hasRole('role_a', 'role_b'));
        $this->assertFalse($this->user->hasRole('role_a', 'role_c'));

        $this->assertTrue($this->user->hasRole('role_a, role_b'));
        $this->assertFalse($this->user->hasRole('role_a, role_c'));

        $this->assertTrue($this->user->hasRole('role_a,role_b'));
        $this->assertFalse($this->user->hasRole('role_a,role_c'));

        $this->assertTrue($this->user->hasRole('role_a & role_b'));
        $this->assertFalse($this->user->hasRole('role_a & role_c'));

        $this->assertTrue($this->user->hasRole('role_a && role_b'));
        $this->assertFalse($this->user->hasRole('role_a && role_c'));

        $this->assertTrue($this->user->hasRole('role_a and role_b'));
        $this->assertFalse($this->user->hasRole('role_a and role_c'));

        $this->assertTrue($this->user->hasRole('role_a AND role_b'));
        $this->assertFalse($this->user->hasRole('role_a AND role_c'));
    }

    public function test_user_only_require_one_roles()
    {
        $this->assertTrue($this->user->hasRole('role_a|role_b'));
        $this->assertTrue($this->user->hasRole('role_a|role_c'));

        $this->assertTrue($this->user->hasRole('role_a | role_b'));
        $this->assertTrue($this->user->hasRole('role_a | role_c'));

        $this->assertTrue($this->user->hasRole('role_a||role_b'));
        $this->assertTrue($this->user->hasRole('role_a||role_c'));

        $this->assertTrue($this->user->hasRole('role_a || role_b'));
        $this->assertTrue($this->user->hasRole('role_a || role_c'));

        $this->assertTrue($this->user->hasRole('role_a or role_b'));
        $this->assertTrue($this->user->hasRole('role_a or role_c'));

        $this->assertTrue($this->user->hasRole('role_a OR role_b'));
        $this->assertTrue($this->user->hasRole('role_a OR role_c'));

        $this->assertFalse($this->user->hasRole('role_c'));
    }
}
