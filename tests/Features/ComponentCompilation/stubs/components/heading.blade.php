<div>
    <div>
        @isset($subheader)
            <h6>
                {{ $subheader }}
            </h6>
        @endisset
        <h1>
            {{ $slot }}
        </h1>
    </div>
</div>