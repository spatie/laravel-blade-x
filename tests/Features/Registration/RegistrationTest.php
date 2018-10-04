<?php

namespace Spatie\BladeX\Tests;

use stdClass;
use Spatie\BladeX\Facades\BladeX;
use Spatie\BladeX\Component;
use Illuminate\Support\Facades\View;
use Spatie\BladeX\Exceptions\CouldNotRegisterComponent;
use Spatie\BladeX\Tests\Features\Registration\TestClasses\SelectViewModel;

class RegistrationTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        View::addLocation(__DIR__.'/stubs');
    }

    /** @test */
    public function it_can_register_a_single_component_by_only_providing_a_view()
    {
        BladeX::component('components.directoryWithComponents.myView1');

        $registeredComponents = BladeX::registeredComponents();

        $this->assertEquals('components.directoryWithComponents.myView1', $registeredComponents[1]->view);
        $this->assertEquals('my-view1', $registeredComponents[1]->tag);
    }

    /** @test */
    public function it_can_register_a_single_component_with_a_custom_tag()
    {
        BladeX::component('components.directoryWithComponents.myView1', 'my-custom-tag');

        $registeredComponents = BladeX::registeredComponents();

        $this->assertCount(2, $registeredComponents);
        $this->assertEquals('components.directoryWithComponents.myView1', $registeredComponents[1]->view);
        $this->assertEquals('my-custom-tag', $registeredComponents[1]->tag);
    }

    /** @test */
    public function it_will_register_a_component_only_once()
    {
        BladeX::component('components.selectField');

        BladeX::component('components.selectField')->viewModel(SelectViewModel::class);

        $registeredComponents = BladeX::registeredComponents();

        $this->assertCount(2, $registeredComponents);
        $this->assertEquals(SelectViewModel::class, $registeredComponents[1]->viewModel);
    }

    /** @test */
    public function it_will_throw_an_exception_for_a_non_existing_view()
    {
        $this->expectException(CouldNotRegisterComponent::class);

        BladeX::component('non-existing-component');
    }

    /** @test */
    public function it_can_register_a_directory_containing_view_components()
    {
        BladeX::component('components.directoryWithComponents.*');

        $registeredComponents = collect(BladeX::registeredComponents())
            ->mapWithKeys(function (Component $bladeXComponent) {
                return [$bladeXComponent->tag => $bladeXComponent->view];
            })
            ->toArray();

        $this->assertEquals([
            'my-view1' => 'components.directoryWithComponents.myView1',
            'my-view2' => 'components.directoryWithComponents.myView2',
            'my-view3' => 'components.directoryWithComponents.myView3',
            'context' => 'bladex::context',
        ], $registeredComponents);
    }

    /** @test */
    public function it_can_register_multiple_directories_containing_view_components()
    {
        BladeX::component([
            'components.directoryWithComponents.*',
            'components.directoryWithComponents2.*',
        ]);

        $registeredComponents = collect(BladeX::registeredComponents())
            ->mapWithKeys(function (Component $bladeXComponent) {
                return [$bladeXComponent->tag => $bladeXComponent->view];
            })
            ->toArray();

        $this->assertEquals([
            'my-view1' => 'components.directoryWithComponents.myView1',
            'my-view2' => 'components.directoryWithComponents.myView2',
            'my-view3' => 'components.directoryWithComponents.myView3',
            'my-view4' => 'components.directoryWithComponents2.myView4',
            'my-view5' => 'components.directoryWithComponents2.myView5',
            'my-view6' => 'components.directoryWithComponents2.myView6',
            'context' => 'bladex::context',
        ], $registeredComponents);
    }

    /** @test */
    public function it_will_throw_an_exception_when_passing_an_invalid_argument_to_component()
    {
        $this->expectException(CouldNotRegisterComponent::class);

        BladeX::component(new stdClass());
    }

    /** @test */
    public function it_will_throw_an_exception_when_registering_a_view_that_does_not_exist()
    {
        $this->expectException(CouldNotRegisterComponent::class);

        BladeX::component('nonExistingView');
    }

    /** @test */
    public function it_will_throw_an_exception_when_registering_a_directory_that_does_not_exist()
    {
        $this->expectException(CouldNotRegisterComponent::class);

        BladeX::component('nonExistingDirectory.*');
    }

    /** @test */
    public function it_can_register_a_namespaced_directory()
    {
        View::addNamespace('namespaced-test', __DIR__.'/stubs/components/namespacedComponents');

        BladeX::component('namespaced-test::*');

        $registeredComponents = collect(BladeX::registeredComponents())
            ->mapWithKeys(function (Component $bladeXComponent) {
                return [$bladeXComponent->tag => $bladeXComponent->view];
            })
            ->toArray();

        dd($registeredComponents);}
}
