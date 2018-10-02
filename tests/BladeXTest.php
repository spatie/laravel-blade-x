<?php

namespace Spatie\BladeX\Tests;

use Spatie\BladeX\Facades\BladeX;
use Spatie\BladeX\BladeXComponent;
use Spatie\BladeX\Exceptions\CouldNotParseBladeXComponent;
use Spatie\BladeX\Exceptions\CouldNotRegisterBladeXComponent;
use Spatie\BladeX\Tests\TestClasses\SelectViewModel;

class BladeXTest extends TestCase
{
    /** @test */
    public function it_can_register_a_single_component_with_providing_a_view_and_component_name()
    {
        BladeX::component('registerDirectoryTest.myView1', 'myView1');

        $registeredComponents = BladeX::getRegisteredComponents();

        $this->assertCount(1, $registeredComponents);
        $this->assertEquals('myView1', $registeredComponents[0]->name);
        $this->assertEquals('registerDirectoryTest/myView1', $registeredComponents[0]->bladeViewName);
    }

    /** @test */
    public function it_can_register_a_single_component_by_only_providing_a_view()
    {
        BladeX::component('registerDirectoryTest.myView1');

        $registeredComponents = BladeX::getRegisteredComponents();

        $this->assertEquals('my-view1', $registeredComponents[0]->name);
        $this->assertEquals('registerDirectoryTest/myView1', $registeredComponents[0]->bladeViewName);
    }

    /** @test */
    public function it_will_register_a_component_only_once()
    {
        BladeX::component('components.select-field');

        BladeX::component('components.select-field')->viewModel(SelectViewModel::class);

        $registeredComponents = BladeX::getRegisteredComponents();

        $this->assertCount(1, $registeredComponents);
        $this->assertEquals(SelectViewModel::class, $registeredComponents[0]->viewModelClass);
    }

    /** @test */
    public function it_will_throw_an_exception_for_a_non_existing_view()
    {
        $this->expectException(CouldNotRegisterBladeXComponent::class);

        BladeX::component('non-existing-component');
    }

    /** @test */
    public function it_can_register_a_directory_containing_view_components()
    {
        BladeX::components($this->getStub('views/registerDirectoryTest'));

        $registeredComponents = collect(BladeX::getRegisteredComponents())
            ->mapWithKeys(function (BladeXComponent $bladeXComponent) {
                return [$bladeXComponent->name => $bladeXComponent->bladeViewName];
            })
            ->toArray();

        $this->assertEquals([
            'my-view1' => 'registerDirectoryTest/myView1',
            'my-view2' => 'registerDirectoryTest/myView2',
            'my-view3' => 'registerDirectoryTest/myView3',
        ], $registeredComponents);
    }

    /** @test */
    public function it_will_throw_an_error_when_registering_a_directory_that_does_not_exist()
    {
        $this->expectException(CouldNotRegisterBladeXComponent::class);

        BladeX::components('non-existing-directory');
    }

    /** @test */
    public function it_compiles_a_regular_component()
    {
        BladeX::component('components.card');

        $this->assertMatchesViewSnapshot('regularComponent');
    }

    /** @test */
    public function it_compiles_a_self_closing_component()
    {
        BladeX::component('components.alert');

        $this->assertMatchesViewSnapshot('selfClosingComponent');
    }

    /** @test */
    public function it_compiles_a_view_with_two_components()
    {
        BladeX::component('components.card');
        BladeX::component('components.textField');

        $this->assertMatchesViewSnapshot('twoComponents');
    }

    /** @test */
    public function it_compiles_a_component_that_is_used_recursively()
    {
        BladeX::component('components.card');

        $this->assertMatchesViewSnapshot('recursiveComponents');
    }

    /** @test */
    public function it_compiles_a_component_with_scoped_slots()
    {
        BladeX::component('components.layout');

        $this->assertMatchesViewSnapshot('componentWithScopedSlots');
    }

    /** @test */
    public function it_compiles_a_component_with_variables()
    {
        BladeX::component('components.card');

        $this->assertMatchesViewSnapshot('componentWithVariables');
    }

    /** @test */
    public function it_compiles_a_component_with_an_unescaped_variable()
    {
        BladeX::component('components.card');

        $this->assertMatchesViewSnapshot('componentWithUnescapedVariables');
    }

    /** @test */
    public function it_works_with_a_global_prefix()
    {
        BladeX::component('components.card');

        BladeX::prefix('x');

        $this->assertMatchesViewSnapshot('globalPrefix');
    }

    /** @test */
    public function it_compiles_components_that_use_a_global_function()
    {
        BladeX::component('components.card');

        $this->assertMatchesViewSnapshot('globalFunction');
    }

    /** @test */
    public function it_compiles_kebas_case_attributes_as_camelcase_variables()
    {
        BladeX::component('components.header');

        $this->assertMatchesViewSnapshot('kebabCaseAttributes');
    }

    /** @test */
    public function it_throws_a_dedicated_exception_for_invalid_components()
    {
        BladeX::component('components.card');

        $this->expectException(CouldNotParseBladeXComponent::class);

        $this->assertMatchesViewSnapshot('invalidComponent');
    }
}
