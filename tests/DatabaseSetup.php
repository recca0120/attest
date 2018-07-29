<?php

namespace Recca0120\Attest\Tests;

use CreateRolesTable;
use CreateUsersTable;
use CreateRoleUserTable;
use CreatePermissionsTable;
use CreatePermissionRoleTable;
use CreatePermissionUserTable;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Illuminate\Database\Capsule\Manager as Capsule;

trait DatabaseSetup
{
    protected function setupDatabase()
    {
        $container = new Container;
        $capsule = new Capsule;
        $capsule->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $capsule->setEventDispatcher(new Dispatcher($container));
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        $container['db'] = $capsule;

        Facade::setFacadeApplication($container);

        $migrations = [
            CreateUsersTable::class,
            CreateRolesTable::class,
            CreateRoleUserTable::class,
            CreatePermissionsTable::class,
            CreatePermissionRoleTable::class,
            CreatePermissionUserTable::class,
        ];

        foreach ($migrations as $migration) {
            $schema = new $migration();
            $schema->up();
        }
    }
}
