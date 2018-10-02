<?php

namespace Spatie\BladeX\Tests;

use Spatie\BladeX\Facades\BladeX;

class BladeXContextTest extends TestCase
{
    /** @test */
    public function components_receive_context_data()
    {
        BladeX::component('components.userName');

        // dd(\Blade::compileString(file_get_contents(__DIR__.'/stubs/views/componentWithContext.blade.php')));

        $this->assertMatchesViewSnapshot('componentWithContext', [
            'user' => (object) [
                'name' => 'Sebastian',
            ],
        ]);
    }
}
