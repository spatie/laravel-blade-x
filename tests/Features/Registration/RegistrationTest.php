<?php

namespace Spatie\BladeX\Tests;

use Illuminate\Support\Facades\View;
use Spatie\BladeX\Facades\BladeX;
use Spatie\BladeX\BladeXComponent;
use Spatie\BladeX\Tests\Features\Registration\TestClasses\SelectViewModel;
use Spatie\BladeX\Exceptions\CouldNotRegisterBladeXComponent;
use stdClass;

class RegistrationTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        View::addLocation(__DIR__ . '/stubs');
    }

    /** @test */
    public function it_can_register_a_single_component_with_providing_a_view_and_component_name()
    {
        BladeX::component('directoryWithComponents.myView1', 'myView1');

        $registeredComponents = BladeX::getRegisteredComponents();

        $this->assertCount(2, $registeredComponents);
        $this->assertEquals('myView1', $registeredComponents[1]->name);
        $this->assertEquals('directoryWithComponents/myView1', $registeredComponents[1]->bladeViewName);
    }

    /** @test */
    public function it_can_register_a_single_component_by_only_providing_a_view()
    {
        BladeX::component('directoryWithComponents.myView1');

        $registeredComponents = BladeX::getRegisteredComponents();

        $this->assertEquals('my-view1', $registeredComponents[1]->name);
        $this->assertEquals('directoryWithComponents/myView1', $registeredComponents[1]->bladeViewName);
    }

    /** @test */
    public function it_will_register_a_component_only_once()
    {
        BladeX::component('components.select-field');

        BladeX::component('components.select-field')->viewModel(SelectViewModel::class);

        $registeredComponents = BladeX::getRegisteredComponents();

        $this->assertCount(2, $registeredComponents);
        $this->assertEquals(SelectViewModel::class, $registeredComponents[1]->viewModelClass);
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
        BladeX::components(__DIR__ . '/stubs/directoryWithComponents');

        $registeredComponents = collect(BladeX::getRegisteredComponents())
            ->mapWithKeys(function (BladeXComponent $bladeXComponent) {
                return [$bladeXComponent->name => $bladeXComponent->bladeViewName];
            })
            ->toArray();

        $this->assertEquals([
            'my-view1' => 'directoryWithComponents/myView1',
            'my-view2' => 'directoryWithComponents/myView2',
            'my-view3' => 'directoryWithComponents/myView3',
            'context' => 'bladex::context',
        ], $registeredComponents);
    }

    /** @test */
    public function it_can_register_multiple_directories_containing_view_components()
    {
        BladeX::components([
            __DIR__ . '/stubs/directoryWithComponents',
            __DIR__ . '/stubs/directoryWithComponents2',
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
            'context' => 'bladex::context',
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
}
