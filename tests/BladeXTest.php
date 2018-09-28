<?php

namespace Spatie\BladeX\Tests;


use Spatie\BladeX\Facades\BladeX;

class BladeXTest extends TestCase
{
    /**
     * TEMP: Proof of concept test that we can replace a custom component
     * with a custom view include.
     *
     * @test
     */
    public function it_can_replace_a_custom_component_with_a_string_temp()
    {
        BladeX::component('data-table', 'test');

        $this->assertBladeCompilesTo(
            'This is a test',
            'data-table-include'
        );
    }

    /** @test */
    public function it_can_register_a_single_component()
    {
        BladeX::component('data-table', 'data-table');

        $registeredComponents = BladeX::getRegisteredComponents();
        $this->assertCount(1, $registeredComponents);
        $this->assertEquals('data-table', $registeredComponents['data-table']);
    }

    /** @test */
    public function it_can_register_a_directory_containing_view_components()
    {
        BladeX::components($this->getStub('views'));

        dd(BladeX::getRegisteredComponents());
    }

    /** @test */
    public function it_can_transpile_a_view_include()
    {
        $this->assertBladeCompilesTo(
            $this->getStub('/views/data-table-include-compiled.blade.php'),
            'data-table-include',
            [
                'users' => [
                    ['Brent', 'brent@spatie.be']
                ],
            ]
        );
    }
}
