<?php

namespace Spatie\BladeX\Tests;


use Spatie\BladeX\Exceptions\CouldNotRegisterComponent;
use Spatie\BladeX\Facades\BladeX;

class BladeXTest extends TestCase
{
    /** @test */
    public function it_can_register_a_single_component_with_providing_a_view_and_component_name()
    {
        BladeX::component('registerDirectoryTest.myView1', 'myView1');

        $registeredComponents = BladeX::getRegisteredComponents();

        $this->assertCount(1, $registeredComponents);
        $this->assertEquals('registerDirectoryTest/myView1', $registeredComponents['myView1']);
    }

    /** @test */
    public function it_can_register_a_single_component_by_only_providing_a_view()
    {
        BladeX::component('registerDirectoryTest.myView1');

        $registeredComponents = BladeX::getRegisteredComponents();

        $this->assertCount(1, $registeredComponents);
        $this->assertEquals('registerDirectoryTest/myView1', $registeredComponents['my-view1']);
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

        $this->assertEquals([
            'my-view1' => 'registerDirectoryTest/myView1',
            'my-view2' => 'registerDirectoryTest/myView2',
            'my-view3' => 'registerDirectoryTest/myView3',
        ], BladeX::getRegisteredComponents());
    }

    /** @test */
    public function it_will_throw_an_error_when_registering_a_directory_that_does_not_exist()
    {
        $this->expectException(CouldNotRegisterComponent::class);

        BladeX::components('non-existing-directory');
    }

    /** @test */
    // public function it_compiles_a_component()
    // {

    // }
}
