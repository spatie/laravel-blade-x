<model-form :model="$user">
    {{-- This does _not_ work because slots are evaluated before the context is updated. --}}
    <user-name />
</model-form>
