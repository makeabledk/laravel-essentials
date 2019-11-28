<?php

namespace Makeable\LaravelEssentials;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class EssentialsServiceProvider extends ServiceProvider
{
    public function register()
    {
        Relation::macro('basename', function ($class) {
            Relation::morphMap([class_basename($class) => $class]);
        });
    }
}