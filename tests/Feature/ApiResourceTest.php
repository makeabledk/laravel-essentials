<?php

namespace Makeable\LaravelEssentials\Tests\Feature;

use App\User;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Makeable\LaravelEssentials\EventListener;
use Makeable\LaravelEssentials\Tests\Stubs\RegisterUserInCRM;
use Makeable\LaravelEssentials\Tests\Stubs\UserRegistered;
use Makeable\LaravelEssentials\Tests\Stubs\UserRegisteredThroughCampaign;
use Makeable\LaravelEssentials\Tests\Stubs\UserResource;
use Makeable\LaravelEssentials\Tests\Stubs\UserWelcomeNotification;
use Makeable\LaravelEssentials\Tests\TestCase;

class ApiResourceTest extends TestCase
{
    use RefreshDatabase;

    /** @test **/
    public function roles_can_be_added_to_a_resource()
    {
        $resource = new UserResource($this->user());
        $this->assertArrayNotHasKey('password', $resource->resolve());

        $resource->as('self');
        $this->assertArrayHasKey('password', $resource->resolve());
    }

    /** @test **/
    public function roles_can_be_added_to_a_collection()
    {
        $this->user();
        $this->user();

        $resources = UserResource::collection(User::all());
        $this->assertArrayNotHasKey('password', array_first($resources->resolve()));

        $resources->as(['self']); // arrays are accepted too
        $this->assertArrayHasKey('password', array_first($resources->resolve()));
    }

    /** @test **/
    public function a_resource_can_check_if_matches_any_role()
    {
        $resource = new UserResource($this->user());
        $this->assertArrayNotHasKey('secret', $resource->resolve());

        $resource->as('developer', 'foo', 'bar'); // multiple arguments are accepted too
        $this->assertArrayHasKey('secret', $resource->resolve());
    }
}
