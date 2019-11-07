<?php

namespace Makeable\LaravelEssentials;

use Illuminate\Contracts\Notifications\Dispatcher;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;

class EventListener
{
    /**
     * @var string
     */
    protected $listenerClass;

    /**
     * @var callable
     */
    protected $callable;

    /**
     * EventListener constructor.
     * @param $listenerClass
     * @param $callable
     */
    public function __construct($listenerClass, $callable = null)
    {
        $this->listenerClass = $listenerClass;
        $this->callable = $callable ?: function () {
        };
    }

    /**
     * @param $event
     * @return mixed
     */
    public function __invoke($event)
    {
        $listener = app()->makeWith($this->listenerClass, get_object_vars($event));

        if ($this->isEvent($listener)) {
            event($listener);
        } elseif ($this->isJob($listener)) {
            call_user_func($this->callable, dispatch($listener));
        } elseif ($this->isNotification($listener)) {
            app(Dispatcher::class)->send($listener->routeNotificationForEvent($event), $listener);
        } else {
            $listener->handle($event);
        }
    }

    /**
     * @param $listener
     * @return bool
     */
    protected function isEvent($listener)
    {
        return Arr::get(class_uses($listener), \Illuminate\Foundation\Events\Dispatchable::class) !== null;
    }

    /**
     * @param $listener
     * @return bool
     */
    protected function isJob($listener)
    {
        return Arr::get(class_uses($listener), \Illuminate\Foundation\Bus\Dispatchable::class) !== null;
    }

    /**
     * @param $listener
     * @return bool
     */
    protected function isNotification($listener)
    {
        return $listener instanceof Notification;
    }
}
