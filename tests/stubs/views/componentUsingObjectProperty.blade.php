@php
    $person = new class
    {
        public $name = 'John';
    };
@endphp

<card :title="$person->name">
    My content
</card>
