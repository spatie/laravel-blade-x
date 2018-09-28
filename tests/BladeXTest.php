<?php

namespace Spatie\BladeX\Tests;

use Spatie\Facades\BladeX\BladeX;

class BladeXTest extends TestCase
{
    /** @test */
    public function it_can_register_a_directory_containing_view_components()
    {
        BladeX::components($this->getStub('views'));
    }
}
