<div>
    <label for="{{ $name }}">{{ $label }}</label>
    <input type="{{ $type ?? 'text' }}" name="{{ $name }}" id="{{ $name }}" value="{{ $value ?? '' }}">
</div>
