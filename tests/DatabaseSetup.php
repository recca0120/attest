<?php

namespace Recca0120\Attest\Tests;

use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;

trait DatabaseSetup
{
    protected function setupDatabase()
    {
        $capsule = new Capsule;
        $capsule->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $capsule->setEventDispatcher(new Dispatcher(new Container));
        $capsule->setAsGlobal();
        $capsule->bootEloquent();

        Capsule::schema()->create('users', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Capsule::schema()->create('roles', function ($table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('title');
            $table->timestamps();
        });

        Capsule::schema()->create('role_user', function ($table) {
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('role_id');
        });
    }
}
