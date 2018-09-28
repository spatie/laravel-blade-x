<table>
    <thead>
        <tr>
            {{ $thead }}
        </tr>
    </thead>
    @foreach($items as $item)
        {{ $tbody(['item' => $item]) }}
    @endforeach
</table>
