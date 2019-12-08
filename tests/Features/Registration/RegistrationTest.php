<?php

namespace Spatie\BladeX\Tests\Features\Registration;

use Illuminate\Support\Facades\View;
use Spatie\BladeX\Component;
use Spatie\BladeX\Exceptions\CouldNotRegisterComponent;
use Spatie\BladeX\Facades\BladeX;
use Spatie\BladeX\Tests\Features\Registration\TestClasses\SelectViewModel;
use Spatie\BladeX\Tests\TestCase;
use stdClass;

class RegistrationTest extends TestCase
{
    public function setUp(): void
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
        $this->assertEquals('my-view1', $registeredComponents[1]->getTag());
    }

    /** @test */
    public function it_can_register_a_single_component_with_a_custom_tag()
    {
        BladeX::component('components.directoryWithComponents.myView1', 'my-custom-tag');

        $registeredComponents = BladeX::registeredComponents();

        $this->assertCount(2, $registeredComponents);
        $this->assertEquals('components.directoryWithComponents.myView1', $registeredComponents[1]->view);
        $this->assertEquals('my-custom-tag', $registeredComponents[1]->getTag());
    }

    /** @test */
    public function it_accepts_a_component_instance()
    {
        $component = Component::make('components.selectField')->tag('my-custom-tag');

        BladeX::component($component);

        $registeredComponents = BladeX::registeredComponents();

        $this->assertCount(2, $registeredComponents);
        $this->assertEquals('components.selectField', $registeredComponents[1]->view);
        $this->assertEquals('my-custom-tag', $registeredComponents[1]->getTag());
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
                return [$bladeXComponent->getTag() => $bladeXComponent->view];
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
                return [$bladeXComponent->getTag() => $bladeXComponent->view];
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
    public function it_will_throw_an_exception_when_registering_a_namespace_that_does_not_exist()
    {
        $this->expectException(CouldNotRegisterComponent::class);

        BladeX::component('non-existing-namespace::*');
    }

    /** @test */
    public function it_will_throw_an_exception_when_registering_components_without_wildcard()
    {
        $this->expectException(CouldNotRegisterComponent::class);

        BladeX::components('components.directoryWithComponents');
    }

    /** @test */
    public function it_can_register_a_directory_containing_namespaced_view_components()
    {
        View::addNamespace('namespaced-test', __DIR__.'/stubs/components/namespacedComponents');

        BladeX::component('namespaced-test::*');

        $registeredComponents = collect(BladeX::registeredComponents())
            ->mapWithKeys(function (Component $bladeXComponent) {
                return [$bladeXComponent->getTag() => $bladeXComponent->view];
            })
            ->toArray();

        $this->assertEquals([
            'context' => 'bladex::context',
            'namespaced-test::namespaced-view1' => 'namespaced-test::namespacedView1',
            'namespaced-test::namespaced-view2' => 'namespaced-test::namespacedView2',
            'namespaced-test::namespaced-view3' => 'namespaced-test::namespacedView3',
        ], $registeredComponents);
    }

    /** @test */
    public function it_can_register_a_subdirectory_containing_namespaced_view_components()
    {
        View::addNamespace('subdirectory-namespaced-test', __DIR__.'/stubs/components/namespacedComponents');

        BladeX::component('subdirectory-namespaced-test::components.*');

        $registeredComponents = collect(BladeX::registeredComponents())
            ->mapWithKeys(function (Component $bladeXComponent) {
                return [$bladeXComponent->getTag() => $bladeXComponent->view];
            })
            ->toArray();

        $this->assertEquals([
            'context' => 'bladex::context',
            'subdirectory-namespaced-test::namespaced-view4' => 'subdirectory-namespaced-test::components.namespacedView4',
        ], $registeredComponents);
    }

    /** @test */
    public function it_overwrites_the_previous_component_when_registering_one_with_the_same_name()
    {
        BladeX::component('components.directoryWithComponents.myView1', 'foo');
        BladeX::component('components.directoryWithComponents.myView2', 'foo');

        $registeredComponents = BladeX::registeredComponents();

        $this->assertEquals('components.directoryWithComponents.myView2', $registeredComponents[1]->view);
        $this->assertEquals('foo', $registeredComponents[1]->getTag());
    }

    /** @test */
    public function it_can_register_a_namespaced_view_component_without_namespace()
    {
        View::addNamespace('namespaced-test', __DIR__.'/stubs/components/namespacedComponents');

        BladeX::component('namespaced-test::namespacedView1')->withoutNamespace();

        $registeredComponents = collect(BladeX::registeredComponents())
            ->mapWithKeys(function (Component $bladeXComponent) {
                return [$bladeXComponent->getTag() => $bladeXComponent->view];
            })
            ->toArray();

        $this->assertEquals([
            'context' => 'bladex::context',
            'namespaced-view1' => 'namespaced-test::namespacedView1',
        ], $registeredComponents);
    }

    /** @test */
    public function it_can_register_a_namespaced_view_component_without_namespace_but_global_prefix()
    {
        View::addNamespace('namespaced-test', __DIR__.'/stubs/components/namespacedComponents');

        BladeX::prefix('x');

        BladeX::component('namespaced-test::namespacedView1')->withoutNamespace();

        $registeredComponents = collect(BladeX::registeredComponents())
            ->mapWithKeys(function (Component $bladeXComponent) {
                return [$bladeXComponent->getTag() => $bladeXComponent->view];
            })
            ->toArray();

        $this->assertEquals([
            'x-context' => 'bladex::context',
            'x-namespaced-view1' => 'namespaced-test::namespacedView1',
        ], $registeredComponents);
    }

    /** @test */
    public function it_can_register_a_namespaced_view_component_without_namespace_but_self_prefix()
    {
        View::addNamespace('namespaced-test', __DIR__.'/stubs/components/namespacedComponents');

        BladeX::component('namespaced-test::namespacedView1')->withoutNamespace()->prefix('ns');

        $registeredComponents = collect(BladeX::registeredComponents())
            ->mapWithKeys(function (Component $bladeXComponent) {
                return [$bladeXComponent->getTag() => $bladeXComponent->view];
            })
            ->toArray();

        $this->assertEquals([
            'context' => 'bladex::context',
            'ns-namespaced-view1' => 'namespaced-test::namespacedView1',
        ], $registeredComponents);
    }

    /** @test */
    public function it_can_register_a_namespaced_view_component_without_namespace_but_self_prefix_overrides_global_prefix()
    {
        View::addNamespace('namespaced-test', __DIR__.'/stubs/components/namespacedComponents');

        BladeX::prefix('x');

        BladeX::component('namespaced-test::namespacedView1')->withoutNamespace()->prefix('ns');

        $registeredComponents = collect(BladeX::registeredComponents())
            ->mapWithKeys(function (Component $bladeXComponent) {
                return [$bladeXComponent->getTag() => $bladeXComponent->view];
            })
            ->toArray();

        $this->assertEquals([
            'x-context' => 'bladex::context',
            'ns-namespaced-view1' => 'namespaced-test::namespacedView1',
        ], $registeredComponents);
    }

    /** @test */
    public function it_can_register_a_directory_containing_namespaced_view_components_without_namespace()
    {
        View::addNamespace('namespaced-test', __DIR__.'/stubs/components/namespacedComponents');

        BladeX::components('namespaced-test::*')->withoutNamespace();

        BladeX::components('namespaced-test::components.*');

        $registeredComponents = collect(BladeX::registeredComponents())
            ->mapWithKeys(function (Component $bladeXComponent) {
                return [$bladeXComponent->getTag() => $bladeXComponent->view];
            })
            ->toArray();

        $this->assertEquals([
            'context' => 'bladex::context',
            'namespaced-view1' => 'namespaced-test::namespacedView1',
            'namespaced-view2' => 'namespaced-test::namespacedView2',
            'namespaced-view3' => 'namespaced-test::namespacedView3',
            'namespaced-test::namespaced-view4' => 'namespaced-test::components.namespacedView4',
        ], $registeredComponents);
    }

    /** @test */
    public function it_can_register_a_directory_containing_namespaced_view_components_without_namespace_but_global_prefix()
    {
        View::addNamespace('namespaced-test', __DIR__.'/stubs/components/namespacedComponents');

        BladeX::prefix('x');

        BladeX::components('namespaced-test::*')->withoutNamespace();

        BladeX::components('namespaced-test::components.*');

        $registeredComponents = collect(BladeX::registeredComponents())
            ->mapWithKeys(function (Component $bladeXComponent) {
                return [$bladeXComponent->getTag() => $bladeXComponent->view];
            })
            ->toArray();

        $this->assertEquals([
            'x-context' => 'bladex::context',
            'x-namespaced-view1' => 'namespaced-test::namespacedView1',
            'x-namespaced-view2' => 'namespaced-test::namespacedView2',
            'x-namespaced-view3' => 'namespaced-test::namespacedView3',
            'x-namespaced-test::namespaced-view4' => 'namespaced-test::components.namespacedView4',
        ], $registeredComponents);
    }

    /** @test */
    public function it_can_register_a_directory_containing_namespaced_view_components_without_namespace_but_self_prefix()
    {
        View::addNamespace('namespaced-test', __DIR__.'/stubs/components/namespacedComponents');

        BladeX::components('namespaced-test::*')->withoutNamespace()->prefix('ns');

        BladeX::components('namespaced-test::components.*');

        $registeredComponents = collect(BladeX::registeredComponents())
            ->mapWithKeys(function (Component $bladeXComponent) {
                return [$bladeXComponent->getTag() => $bladeXComponent->view];
            })
            ->toArray();

        $this->assertEquals([
            'context' => 'bladex::context',
            'ns-namespaced-view1' => 'namespaced-test::namespacedView1',
            'ns-namespaced-view2' => 'namespaced-test::namespacedView2',
            'ns-namespaced-view3' => 'namespaced-test::namespacedView3',
            'namespaced-test::namespaced-view4' => 'namespaced-test::components.namespacedView4',
        ], $registeredComponents);
    }

    /** @test */
    public function it_can_register_a_directory_containing_namespaced_view_components_without_namespace_but_self_prefix_overrides_global_prefix()
    {
        View::addNamespace('namespaced-test', __DIR__.'/stubs/components/namespacedComponents');

        BladeX::prefix('x');

        BladeX::components('namespaced-test::*')->withoutNamespace()->prefix('ns');

        BladeX::components('namespaced-test::components.*')->withoutNamespace()->prefix('nsc');

        $registeredComponents = collect(BladeX::registeredComponents())
            ->mapWithKeys(function (Component $bladeXComponent) {
                return [$bladeXComponent->getTag() => $bladeXComponent->view];
            })
            ->toArray();

        $this->assertEquals([
            'x-context' => 'bladex::context',
            'ns-namespaced-view1' => 'namespaced-test::namespacedView1',
            'ns-namespaced-view2' => 'namespaced-test::namespacedView2',
            'ns-namespaced-view3' => 'namespaced-test::namespacedView3',
            'nsc-namespaced-view4' => 'namespaced-test::components.namespacedView4',
        ], $registeredComponents);
    }

    /** @test */
    public function it_can_register_multiple_directories_containing_view_components_with_prefix()
    {
        BladeX::components([
            'components.directoryWithComponents.*',
            'components.directoryWithComponents2.*',
        ])->prefix('c');

        $registeredComponents = collect(BladeX::registeredComponents())
            ->mapWithKeys(function (Component $bladeXComponent) {
                return [$bladeXComponent->getTag() => $bladeXComponent->view];
            })
            ->toArray();

        $this->assertEquals([
            'c-my-view1' => 'components.directoryWithComponents.myView1',
            'c-my-view2' => 'components.directoryWithComponents.myView2',
            'c-my-view3' => 'components.directoryWithComponents.myView3',
            'c-my-view4' => 'components.directoryWithComponents2.myView4',
            'c-my-view5' => 'components.directoryWithComponents2.myView5',
            'c-my-view6' => 'components.directoryWithComponents2.myView6',
            'context' => 'bladex::context',
        ], $registeredComponents);
    }

    /** @test */
    public function it_can_register_multiple_views_with_prefix()
    {
        BladeX::components([
            'components.directoryWithComponents.myView1',
            'components.directoryWithComponents.myView2',
            'components.directoryWithComponents.myView3',
        ])->prefix('c');

        BladeX::components([
            'components.directoryWithComponents2.myView4',
            'components.directoryWithComponents2.myView5',
            'components.directoryWithComponents2.myView6',
        ]);

        $registeredComponents = collect(BladeX::registeredComponents())
            ->mapWithKeys(function (Component $bladeXComponent) {
                return [$bladeXComponent->getTag() => $bladeXComponent->view];
            })
            ->toArray();

        $this->assertEquals([
            'c-my-view1' => 'components.directoryWithComponents.myView1',
            'c-my-view2' => 'components.directoryWithComponents.myView2',
            'c-my-view3' => 'components.directoryWithComponents.myView3',
            'my-view4' => 'components.directoryWithComponents2.myView4',
            'my-view5' => 'components.directoryWithComponents2.myView5',
            'my-view6' => 'components.directoryWithComponents2.myView6',
            'context' => 'bladex::context',
        ], $registeredComponents);
    }

    /** @test */
    public function it_can_register_a_directory_and_subdirectories_containing_namespaced_view_components()
    {
        View::addNamespace('namespaced-test', __DIR__.'/stubs/components/namespacedComponents');

        BladeX::component('namespaced-test::**.*');

        $registeredComponents = collect(BladeX::registeredComponents())
            ->mapWithKeys(function (Component $bladeXComponent) {
                return [$bladeXComponent->getTag() => $bladeXComponent->view];
            })
            ->toArray();

        $this->assertEquals([
            'context' => 'bladex::context',
            'namespaced-test::namespaced-view1' => 'namespaced-test::namespacedView1',
            'namespaced-test::namespaced-view2' => 'namespaced-test::namespacedView2',
            'namespaced-test::namespaced-view3' => 'namespaced-test::namespacedView3',
            'namespaced-test::namespaced-view4' => 'namespaced-test::components.namespacedView4',
        ], $registeredComponents);
    }
}
