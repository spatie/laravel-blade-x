<input
    type="checkbox"
    name="{{ $name ?? '' }}"
    {!! ($checked ?? false) ? 'checked="checked"' : '' !!}
/>
