

@php
    $user = 'John';
@endphp

<card title="{{ $user }}">
    My content
</card>


@php
    $user = new class {
        public $name = 'Jane';
    };
@endphp

<card title="{{ $user->name }}">
    My content
</card>

@php
    $title = 'Blade & XML';
@endphp

<card title="{{ $title }}">
    My content
</card>

@php
    $title = 'Blade &amp; XML';
@endphp

<card title="{!! $title !!}">
    My content
</card>
