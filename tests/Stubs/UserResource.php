<?php

namespace Makeable\LaravelEssentials\Tests\Stubs;

use Makeable\LaravelEssentials\Resources\Resource;

class UserResource extends Resource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,

            $this->mergeWhen($this->is('self'), [
                'password' => 'superman'
            ]),

            $this->mergeWhen($this->isEither('self', 'developer'), [
                'secret' => 'foo'
            ])
        ];
    }
}