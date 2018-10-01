@php
$title = 'my title'
@endphp

<x-card title="{{ ucfirst($title) }}">
    {{ ucfirst($title) }}
</x-card>

