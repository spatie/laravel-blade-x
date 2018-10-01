@php
$title = 'my title'
@endphp

<card title="{{ str_after(ucfirst($title), 'My ') }}" />

