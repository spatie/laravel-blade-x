<?php

namespace Spatie\BladeX\Tests;


use Spatie\BladeX\Facades\BladeX;

class BladeXTest extends TestCase
{
    /** @test */
    public function it_can_register_a_single_component()
    {
        BladeX::component('myView1', 'registerDirectoryTest.myView1');

        $registeredComponents = BladeX::getRegisteredComponents();

        $this->assertCount(1, $registeredComponents);
        $this->assertEquals('registerDirectoryTest.myView1', $registeredComponents['myView1']);
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
    // public function it_compiles_a_component()
    // {

    // }
}
