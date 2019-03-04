<?php

namespace Makeable\LaravelEssentials\Tests\Feature;

use Makeable\LaravelEssentials\DiscoverClasses;
use Makeable\LaravelEssentials\Resources\Resource;
use Makeable\LaravelEssentials\Tests\Stubs\UserResource;
use Makeable\LaravelEssentials\Tests\TestCase;

class DiscoverClassesTest extends TestCase
{
    /** @test **/
    public function it_discovers_classes_in_path_and_namespace()
    {
        $classes = DiscoverClasses::in(__DIR__.'/../Stubs', 'Makeable\\LaravelEssentials\\Tests\\Stubs')->get();

        $this->assertGreaterThan(1, $classes->count());
        $this->assertTrue($classes->contains(UserResource::class));
    }

    /** @test **/
    public function it_filters_to_classes_of_a_given_type()
    {
        $classes = DiscoverClasses::in(__DIR__.'/../Stubs', 'Makeable\\LaravelEssentials\\Tests\\Stubs')
            ->of(Resource::class)
            ->get();

        $this->assertEquals(1, $classes->count());
        $this->assertTrue($classes->contains(UserResource::class));
    }
}