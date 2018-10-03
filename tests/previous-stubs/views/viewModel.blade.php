@php
    $countries = [
        'be' => 'Belgium',
        'fr' => 'France',
        'nl' => 'The Netherlands',
    ];
@endphp

<select-field name="countries" :options="$countries" selected="fr" />
