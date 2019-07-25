@php
$title = 'my title'
@endphp

<card :title="Illuminate\Support\Str::after(ucfirst($title), 'My ')">
    {{ $title }}
</card>
