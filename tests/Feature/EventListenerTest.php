<?php

namespace Makeable\LaravelEssentials\Tests\Feature;

use Makeable\LaravelEssentials\Tests\Stubs\User;
use Illuminate\Foundation\Bus\PendingDispatch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;
use Makeable\LaravelEssentials\EventListener;
use Makeable\LaravelEssentials\Tests\Stubs\RegisterUserInCRM;
use Makeable\LaravelEssentials\Tests\Stubs\UserRegistered;
use Makeable\LaravelEssentials\Tests\Stubs\UserRegisteredThroughCampaign;
use Makeable\LaravelEssentials\Tests\Stubs\UserWelcomeNotification;
use Makeable\LaravelEssentials\Tests\TestCase;

class EventListenerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();
    }

    /** @test **/
    public function it_dispatches_jobs()
    {
        app()->instance('registered_crm_user', new User());

        Event::listen(UserRegistered::class, new EventListener(RegisterUserInCRM::class));

        $this->assertFalse(app('registered_crm_user')->exists);

        event(new UserRegistered($user = $this->user()));

        $this->assertTrue($user->is(app('registered_crm_user')));
    }

    /** @test **/
    public function a_dispatching_job_can_be_customized_in_a_callable()
    {
        app()->instance('registered_crm_user', new User());

        $this->expectExceptionMessage('callback executed with pending dispatch');

        Event::listen(UserRegistered::class, new EventListener(RegisterUserInCRM::class, function (PendingDispatch $job) {
            throw new \Exception('callback executed with pending dispatch');
        }));

        event(new UserRegistered($user = $this->user()));

        $this->assertTrue($user->is(app('registered_crm_user')));
    }

    /** @test **/
    public function it_dispatches_notifications()
    {
        Event::listen(UserRegistered::class, new EventListener(UserWelcomeNotification::class));

        event(new UserRegistered($user = $this->user()));

        Notification::assertSentTo($user, UserWelcomeNotification::class);
    }

    /** @test */
    public function it_proxies_events()
    {
        Event::listen(UserRegisteredThroughCampaign::class, new EventListener(UserRegistered::class));

        $proxiedUser = null;

        Event::listen(UserRegistered::class, function ($event) use (&$proxiedUser) {
            $proxiedUser = $event->user;
        });

        event(new UserRegisteredThroughCampaign($user = $this->user()));

        $this->assertTrue($user->is($proxiedUser));
    }
}
