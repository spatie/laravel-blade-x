<?php

namespace Spatie\BladeX\Tests\previousTestClasses;

use Spatie\BladeX\ViewModel;

class UserNameViewModel extends ViewModel
{
    /** @var object */
    public $user;

    public function __construct(object $user)
    {
        $this->user = (object) ['name' => strtoupper($user->name)];
    }
}
