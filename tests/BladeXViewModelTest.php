<?php

namespace Spatie\BladeX\Tests;

use Spatie\BladeX\Facades\BladeX;
use Spatie\BladeX\BladeXComponent;
use Spatie\BladeX\Exceptions\CouldNotParseBladeXComponent;
use Spatie\BladeX\Exceptions\CouldNotRegisterBladeXComponent;
use Spatie\BladeX\Tests\TestClasses\InvalidViewModel;
use Spatie\BladeX\Tests\TestClasses\SelectViewModel;

class BladeXViewModelTest extends TestCase
{
    /** @test */
    public function it_can_register_a_component_with_a_view_model()
    {
        BladeX::component('components.select-field')->viewModel(SelectViewModel::class);

        $registeredComponents = BladeX::getRegisteredComponents();

        $this->assertCount(1, $registeredComponents);
        $this->assertEquals(SelectViewModel::class, $registeredComponents[0]->viewModelClass);
    }

    /** @test */
    public function it_will_return_an_exception_if_a_view_model_class_does_not_exist()
    {
        $this->expectException(CouldNotRegisterBladeXComponent::class);

        BladeX::component('components.select-field')->viewModel('non-existing-class');
    }

    /** @test */
    public function it_will_return_an_exception_if_a_view_model_class_does_not_implement_arrayable()
    {
        $this->expectException(CouldNotRegisterBladeXComponent::class);

        BladeX::component('components.select-field')->viewModel(InvalidViewModel::class);

    }
}