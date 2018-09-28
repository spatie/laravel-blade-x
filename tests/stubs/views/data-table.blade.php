<table>
    <thead>
        <tr>
            {{ $thead }}
        </tr>
    </thead>
    <tbody>
        @foreach($items as $item)
            <tr>
                {{ $tbody(['item' => $item]) }}
            </tr>
        @endforeach
    </tbody>
</table>
