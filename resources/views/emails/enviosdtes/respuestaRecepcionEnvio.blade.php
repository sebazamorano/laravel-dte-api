@component('mail::message')
    @php
        /* @var \App\Models\Email $email */
    @endphp
    {!! $email->html !!}

    Gracias,<br>
    {{ config('app.name') }}
@endcomponent
