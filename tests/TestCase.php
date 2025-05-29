<?php

namespace Makeable\LaravelEssentials\Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Hash;
use Makeable\LaravelEssentials\Tests\Stubs\User;

class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        putenv('APP_ENV=testing');
        putenv('DB_CONNECTION=sqlite');
        putenv('DB_DATABASE=:memory:');
        putenv('QUEUE_CONNECTION=sync');

        $app = require __DIR__.'/../vendor/laravel/laravel/bootstrap/app.php';
        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        Hash::setRounds(4);

        return $app;
    }

    /**
     * @return User
     */
    protected function user()
    {
        $user = (new User)->forceFill([
            'name' => 'Foo',
            'email' => uniqid('foo-').'@example.org',
            'password' => bcrypt('foo'),
        ]);
        $user->save();

        return $user;
    }
}
