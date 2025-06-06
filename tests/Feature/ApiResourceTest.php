<?php

namespace Makeable\LaravelEssentials\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;
use Makeable\LaravelEssentials\Tests\Stubs\User;
use Makeable\LaravelEssentials\Tests\Stubs\UserResource;
use Makeable\LaravelEssentials\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ApiResourceTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function roles_can_be_added_to_a_resource()
    {
        $resource = new UserResource($this->user());
        $this->assertArrayNotHasKey('password', $resource->resolve());

        $resource->as('self');
        $this->assertArrayHasKey('password', $resource->resolve());
    }

    #[Test]
    public function roles_can_be_added_to_a_collection()
    {
        $this->user();
        $this->user();

        $resources = UserResource::collection(User::all());
        $this->assertArrayNotHasKey('password', Arr::first($resources->resolve()));

        $resources->as(['self']); // arrays are accepted too
        $this->assertArrayHasKey('password', Arr::first($resources->resolve()));
    }

    #[Test]
    public function a_resource_can_check_if_matches_any_role()
    {
        $resource = new UserResource($this->user());
        $this->assertArrayNotHasKey('secret', $resource->resolve());

        $resource->as('developer', 'foo', 'bar'); // multiple arguments are accepted too
        $this->assertArrayHasKey('secret', $resource->resolve());
    }
}
