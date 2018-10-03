<?php

namespace Spatie\BladeX\Tests\previousTestClasses;

use Spatie\BladeX\ViewModel;

class UserProviderViewModel extends ViewModel
{
    public function user(): object
    {
        return (object) [
            'name' => 'Sebastian',
        ];
    }
}
