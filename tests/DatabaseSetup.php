<?php

namespace Recca0120\Attest\Tests;

use CreatePermissibleTable;
use CreatePermissionsTable;
use CreateRoleableTable;
use CreateRolesTable;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Schema;

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

        Schema::create('users', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        $migrations = [
            CreateRolesTable::class,
            CreateRoleableTable::class,
            CreatePermissionsTable::class,
            CreatePermissibleTable::class,
        ];

        foreach ($migrations as $migration) {
            $schema = new $migration();
            $schema->up();
        }
    }
}
