<div class="card">
    <h1>{!! is_array($title) ? $title[0] : $title !!}</h1>
    <div>{{ $slot }}</div>
</div>
