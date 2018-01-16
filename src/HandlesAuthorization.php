<?php

namespace Makeable\LaravelEssentials;

trait HandlesAuthorization
{
    use \Illuminate\Auth\Access\HandlesAuthorization;

    /**
     * @param $condition
     * @param  string  $message
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function denyIf($condition, $message = null)
    {
        if ($condition) {
            $this->deny($message);
        }
    }

    /**
     * @param $condition
     * @param  string  $message
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function denyUnless($condition, $message = null)
    {
        if (! $condition) {
            $this->deny($message);
        }
    }
}
