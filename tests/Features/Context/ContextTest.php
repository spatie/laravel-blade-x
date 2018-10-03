<?php

namespace Spatie\BladeX\Tests\Features\Context;

use Spatie\BladeX\Facades\BladeX;
use Spatie\BladeX\Tests\TestCase;
use Illuminate\Support\Facades\View;
use Spatie\BladeX\Tests\Features\Context\TestClasses\UserNameViewModel;
use Spatie\BladeX\Tests\Features\Context\TestClasses\UserProviderViewModel;

class ContextTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        View::addLocation(__DIR__.'/stubs');
    }

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
    public function components_receive_context_data_from_a_view_model()
    {
        BladeX::component('components.userName')
            ->viewModel(UserNameViewModel::class);

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

    /** @test */
    public function components_cannot_provide_context_and_consume_it_in_a_slot()
    {
        BladeX::component('components.userName');
        BladeX::component('components.modelForm')
            ->viewModel(UserProviderViewModel::class);

        $this->expectExceptionMessage('Undefined variable: user');

        view('views.componentWithDefaultSlotThatUsesContext', [
            'user' => (object) [
                'name' => 'Sebastian',
            ],
        ])->render();
    }

    /** @test */
    public function components_cannot_provide_context_and_consume_it_in_a_named_slot()
    {
        BladeX::component('components.userName');
        BladeX::component('components.modelForm')
            ->viewModel(UserProviderViewModel::class);

        $this->expectExceptionMessage('Undefined variable: user');

        view('views.componentWithNamedSlotThatUsesContext', [
            'user' => (object) [
                'name' => 'Sebastian',
            ],
        ])->render();
    }
}
