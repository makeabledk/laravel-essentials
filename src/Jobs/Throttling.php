<?php

namespace Services\Support;

trait Throttling
{
    /**
     * @var array
     */
    public $throttleOptions;

    /**
     * @return Throttle
     */
    public function configure()
    {
        return $this->throttle();
    }

    /**
     * @return Throttle
     */
    public function throttle()
    {
        return Throttle::job($this, config("queue.default") === 'redis');
    }
}
