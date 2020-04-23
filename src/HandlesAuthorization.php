<?php

namespace Makeable\LaravelEssentials;

trait HandlesAuthorization
{
    use \Illuminate\Auth\Access\HandlesAuthorization;

    /**
     * @param  mixed  $condition
     * @param  string  $message
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function denyIf($condition, $message = null)
    {
        abort_if($condition, $this->deny($message));
    }

    /**
     * @param  mixed  $condition
     * @param  string  $message
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function denyUnless($condition, $message = null)
    {
        abort_unless($condition, $this->deny($message));
    }
}
