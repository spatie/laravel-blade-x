<?php

namespace Spatie\BladeX\Tests;

use Spatie\BladeX\Facades\BladeX;
use Spatie\BladeX\BladeXComponent;
use Spatie\BladeX\Tests\TestClasses\SelectViewModel;
use Spatie\BladeX\Exceptions\CouldNotParseBladeXComponent;
use Spatie\BladeX\Exceptions\CouldNotRegisterBladeXComponent;
use stdClass;

class BladeXTest extends TestCase
{
    /** @test */
    public function it_can_register_a_single_component_with_providing_a_view_and_component_name()
    {
        BladeX::component('directoryWithComponents.myView1', 'myView1');

        $registeredComponents = BladeX::getRegisteredComponents();

        $this->assertCount(1, $registeredComponents);
        $this->assertEquals('myView1', $registeredComponents[0]->name);
        $this->assertEquals('directoryWithComponents/myView1', $registeredComponents[0]->bladeViewName);
    }

    /** @test */
    public function it_can_register_a_single_component_by_only_providing_a_view()
    {
        BladeX::component('directoryWithComponents.myView1');

        $registeredComponents = BladeX::getRegisteredComponents();

        $this->assertEquals('my-view1', $registeredComponents[0]->name);
        $this->assertEquals('directoryWithComponents/myView1', $registeredComponents[0]->bladeViewName);
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
        BladeX::components($this->getStub('directoryWithComponents'));

        $registeredComponents = collect(BladeX::getRegisteredComponents())
            ->mapWithKeys(function (BladeXComponent $bladeXComponent) {
                return [$bladeXComponent->name => $bladeXComponent->bladeViewName];
            })
            ->toArray();

        $this->assertEquals([
            'my-view1' => 'directoryWithComponents/myView1',
            'my-view2' => 'directoryWithComponents/myView2',
            'my-view3' => 'directoryWithComponents/myView3',
        ], $registeredComponents);
    }

    /** @test */
    public function it_can_register_multiple_directories_containing_view_components()
    {
        BladeX::components([
            $this->getStub('directoryWithComponents'),
            $this->getStub('directoryWithComponents2'),
        ]);

        $registeredComponents = collect(BladeX::getRegisteredComponents())
            ->mapWithKeys(function (BladeXComponent $bladeXComponent) {
                return [$bladeXComponent->name => $bladeXComponent->bladeViewName];
            })
            ->toArray();

        $this->assertEquals([
            'my-view1' => 'directoryWithComponents/myView1',
            'my-view2' => 'directoryWithComponents/myView2',
            'my-view3' => 'directoryWithComponents/myView3',
            'my-view4' => 'directoryWithComponents2/myView4',
            'my-view5' => 'directoryWithComponents2/myView5',
            'my-view6' => 'directoryWithComponents2/myView6',
        ], $registeredComponents);
    }

    public function it_will_throw_an_exception_when_passing_an_invalid_argument_to_components()
    {
        BladeX::components(new stdClass());
    }

    /** @test */
    public function it_will_throw_an_exception_when_registering_a_directory_that_does_not_exist()
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
    public function it_compiles_a_component_that_uses_an_object_property_as_value()
    {
        BladeX::component('components.card');

        $this->assertMatchesViewSnapshot('componentUsingObjectProperty');
    }

    /** @test */
    public function it_compiles_a_component_with_an_unescaped_variable()
    {
        BladeX::component('components.card');

        $this->assertMatchesViewSnapshot('componentWithUnescapedVariables');
    }

    /** @test */
    public function it_compiles_a_component_with_a_quoteless_attribute()
    {
        BladeX::component('components.card');

        $this->assertMatchesViewSnapshot('componentWithQuotelessAttribute');
    }

    /** @test */
    public function it_compiles_a_component_with_a_spaceship_operator()
    {
        BladeX::component('components.card');

        $this->assertMatchesViewSnapshot('componentWithSpaceshipOperatorInAttribute');
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
