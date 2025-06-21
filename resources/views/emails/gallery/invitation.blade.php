@component('mail::message')
# You're Invited!

Hello,

{{ $inviterName }} has invited you to collaborate on the gallery: **{{ $galleryName }}**.

Click the button below to accept the invitation:
@component('mail::button', ['url' => $acceptUrl])
Accept Invitation
@endcomponent

If you do not wish to accept this invitation, you can reject it using the button below:
@component('mail::button', ['url' => $rejectUrl, 'color' => 'error'])
Reject Invitation
@endcomponent

Alternatively, you can use the following link to accept:
[{{ $acceptUrl }}]({{ $acceptUrl }})

If you did not expect this invitation, you can safely ignore this email. Your account will not be affected.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
