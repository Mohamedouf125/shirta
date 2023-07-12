@component('mail::message')
<img src="{{$image}}" alt="logo">
Dear customer, the one time password (OTP) to reset your password at <strong>SHIRTA</strong> is (<strong>{{$OTP}}</strong>). This OTP will expire in 5 minutes.
Thanks,<br>
{{ config('app.name') }}
@endcomponent



