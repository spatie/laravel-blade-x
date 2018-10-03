<form>
    {{-- This does _not_ work because slots are evaluated before the context is updated. --}}
    <context :model="$model">
        {{ $slot }}
        <button type="submit">
            Submit
        </button>
        {{ $actions ?? '' }}
    </context>
</form>
