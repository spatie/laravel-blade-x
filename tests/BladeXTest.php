<?php

namespace Spatie\BladeX\Tests;

use Spatie\BladeX\Facades\BladeX;
use Spatie\BladeX\BladeXComponent;
use Spatie\BladeX\Exceptions\CouldNotRegisterComponent;

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
    public function it_will_throw_an_excepting_for_a_non_existing_view()
    {
        $this->expectException(CouldNotRegisterComponent::class);

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
        $this->expectException(CouldNotRegisterComponent::class);

        BladeX::components('non-existing-directory');
    }

    /** @test */
    public function it_compiles_a_regular_component()
    {
        BladeX::component('components.card');

        $this->assertMatchesXmlSnapshot(
            view('templates.profile')->render()
        );
    }

    /** @test */
    public function it_compiles_a_self_closing_component()
    {
        BladeX::component('components.alert');

        $this->assertMatchesXmlSnapshot(
            view('templates.alert')->render()
        );
    }

    /** @test */
    public function it_compiles_a_view_with_two_components()
    {
        BladeX::component('components.card');
        BladeX::component('components.textField');

        $this->assertMatchesXmlSnapshot(
            view('templates.profileList')->render()
        );
    }

    /** @test */
    public function it_compiles_a_component_with_scoped_slots()
    {
        BladeX::component('components.layout');

        $this->assertMatchesXmlSnapshot(
            view('templates.layout')->render()
        );
    }

    /** @test */
    public function it_compiles_a_component_with_variables()
    {
        BladeX::component('components.card');

        $this->assertMatchesXmlSnapshot(
            view('templates.dynamicProfile')->render()
        );
    }
}
