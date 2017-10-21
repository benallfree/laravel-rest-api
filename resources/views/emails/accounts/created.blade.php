@component('mail::message')
# Welcome to Neighborplate!

{$user->username}, we're so happy you've joined the Neighborplate community.

Go ahead and order a dinner on us!

@component('mail::button', ['url' => ''])
Button Text
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
