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
        if ($condition) {
            $this->deny($message)->authorize();
        }
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
        if (! $condition) {
            $this->deny($message)->authorize();
        }
    }
}
