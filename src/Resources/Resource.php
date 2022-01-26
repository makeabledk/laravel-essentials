<?php

namespace Makeable\LaravelEssentials\Resources;

use Illuminate\Http\Resources\MergeValue;
use Illuminate\Support\Arr;

class Resource extends \Illuminate\Http\Resources\Json\JsonResource
{
    /**
     * @var array
     */
    protected $roles = [];

    /**
     * Create new anonymous resource collection.
     *
     * @param  mixed  $resource
     * @return AnonymousResourceCollection
     */
    public static function collection($resource)
    {
        return new AnonymousResourceCollection($resource, get_called_class());
    }

    /**
     * @param  mixed  ...$roles
     * @return $this
     */
    public function as(...$roles)
    {
        $this->roles = array_merge($this->roles, $this->normalizeArray($roles));

        return $this;
    }

    /**
     * @param $role
     * @return bool
     */
    public function is($role)
    {
        return in_array($role, $this->roles);
    }

    /**
     * @param  array  $roles
     * @return bool
     */
    public function isEither(...$roles)
    {
        $roles = $this->normalizeArray($roles);

        foreach ($roles as $role) {
            if ($this->is($role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Merge the given attributes or all none-hidden attributes on the model.
     *
     * @param  array  $attributes
     * @return \Illuminate\Http\Resources\MergeValue
     */
    protected function attributes($attributes = null)
    {
        if ($attributes === null) {
            return new MergeValue($this->resource->attributesToArray());
        }

        return parent::attributes($attributes);
    }

    /**
     * Merge all none-hidden attributes on the model except given keys.
     *
     * @param  array  $attributes
     * @return \Illuminate\Http\Resources\MergeValue
     */
    protected function attributesExcept($attributes)
    {
        return new MergeValue(
            Arr::except($this->resource->attributesToArray(), is_array($attributes) ? $attributes : func_get_args())
        );
    }

    /**
     * @param $array
     * @return array
     */
    protected function normalizeArray($array)
    {
        if (count($array) === 1) {
            return Arr::wrap($array[0]);
        }

        return $array;
    }
}
