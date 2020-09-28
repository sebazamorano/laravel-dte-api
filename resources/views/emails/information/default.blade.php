@component('mail::message')
# {{ $details['title'] }}

{{ $details['message'] }}

Gracias,<br>
{{ config('app.name') }}
@endcomponent
