<?php

namespace Spatie\BladeX\Tests;

use Spatie\BladeX\Facades\BladeX;

class BladeXContextTest extends TestCase
{
    /** @test */
    public function components_receive_context_data()
    {
        BladeX::component('components.userName');

        $this->assertMatchesViewSnapshot('componentWithContext', [
            'user' => (object) [
                'name' => 'Sebastian',
            ],
        ]);
    }

    /** @test */
    public function components_receive_nested_context_data()
    {
        BladeX::component('components.userName');

        $this->assertMatchesViewSnapshot('componentWithNestedContext', [
            'user' => (object) [
                'name' => 'Sebastian',
            ],
            'nestedUser' => (object) [
                'name' => 'Freek',
            ],
        ]);
    }

    /** @test */
    public function components_can_override_context_data()
    {
        BladeX::component('components.userName');

        $this->assertMatchesViewSnapshot('componentWithOverriddenContext', [
            'user' => (object) [
                'name' => 'Sebastian',
            ],
            'overrideUser' => (object) [
                'name' => 'Freek',
            ],
        ]);
    }
}
