<?php

namespace Makeable\LaravelEssentials;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ReflectionClass;
use Symfony\Component\Finder\Finder;

class DiscoverClasses
{
    /**
     * @var Collection
     */
    protected $classes;

    /**
     * @param  Collection|array  $classes
     */
    public function __construct($classes)
    {
        $this->classes = collect($classes);
    }

    /**
     * @param  $name
     * @param  $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        return $this->classes->$name(...$arguments);
    }

    /**
     * @param  $path
     * @param  $namespace
     * @return DiscoverClasses
     */
    public static function in($path, $namespace)
    {
        $classes = [];

        foreach ((new Finder)->in($path)->files() as $class) {
            $class = static::getClassNamespace($namespace, Str::after($class->getPathname(), $path.DIRECTORY_SEPARATOR));

            if (class_exists($class) && ! (new ReflectionClass($class))->isAbstract()) {
                $classes[] = $class;
            }
        }

        return new static($classes);
    }

    /**
     * @param  $type
     * @return DiscoverClasses
     */
    public function of($type)
    {
        $this->classes = $this->classes->filter(function ($class) use ($type) {
            return is_subclass_of($class, $type);
        });

        return $this;
    }

    /**
     * @return Collection
     */
    public function get()
    {
        return $this->classes;
    }

    /**
     * @param  $rootNamespace
     * @param  $relativePath
     * @return string
     */
    protected static function getClassNamespace($rootNamespace, $relativePath)
    {
        $rootNamespace = trim($rootNamespace, '\\').'\\';

        return $rootNamespace.str_replace(['/', '.php'], ['\\', ''], $relativePath);
    }
}
