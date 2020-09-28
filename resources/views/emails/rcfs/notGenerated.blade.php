@component('mail::message')
# Problemas con la generación del RCF [ {{ $details['message'] }} ]

{{ $details['message'] }}

{{ $details['company'] }}

Gracias,<br>
{{ config('app.name') }}
@endcomponent
