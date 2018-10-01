<select name="{{ $name }}">
    @foreach($options as $value => $label)
        <option {!! $isSelected($value) ? 'selected="selected"' : '' !!} name="{{ $value }}">{{ $label }}</option>
    @endforeach
</select>