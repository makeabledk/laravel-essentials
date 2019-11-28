<?php

namespace Makeable\LaravelEssentials;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class EssentialsServiceProvider extends ServiceProvider
{
    public function register()
    {
        Relation::macro('basename', function ($class) {
            Relation::morphMap([class_basename($class) => $class]);
        });
    }
}
