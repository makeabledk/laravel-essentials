<?php

namespace Makeable\LaravelEssentials;

use Illuminate\Contracts\Notifications\Dispatcher;
use Illuminate\Notifications\Notification;

class EventListener
{
    /**
     * @var
     */
    protected $listenerClass;

    /**
     * EventListener constructor.
     * @param $listenerClass
     */
    public function __construct($listenerClass)
    {
        $this->listenerClass = $listenerClass;
    }

    /**
     * @param $event
     * @return mixed
     */
    public function __invoke($event)
    {
        $listener = app()->makeWith($this->listenerClass, get_object_vars($event));

        if ($this->isDispatchable($listener)) {
            dispatch($listener);
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
    protected function isDispatchable($listener)
    {
        return method_exists($listener, 'dispatch');
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
