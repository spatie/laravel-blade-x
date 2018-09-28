<?php

namespace Spatie\BladeX\Tests;

class BladeXTest extends TestCase
{
    /** @test */
    public function it_can_register_a_directory_containing_view_components()
    {

    }

    /** @test */
    public function it()
    {
        $this->assertBladeCompilesTo(<<<HTML
This is a test
HTML
            , 'test');
    }
}
