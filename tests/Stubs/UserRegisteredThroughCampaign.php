<?php

namespace Makeable\LaravelEssentials\Tests\Stubs;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class UserRegisteredThroughCampaign
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var \App\User
     */
    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(\App\User $user)
    {
        $this->user = $user;
    }
}
