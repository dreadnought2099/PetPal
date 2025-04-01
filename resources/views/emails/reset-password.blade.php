@component('mail::message')
# Password Reset Request


Hello,  <span style="color: #49b451;">**{{ $user->name }}**</span>

You requested to reset your password. Click the button below to proceed:

@component('mail::button', ['url' => route('password.reset', ['token' => $token, 'email' => $email])])
<style>
    .button {
        background-color: #212121;
        color: white;
    }
</style>
Reset Password
@endcomponent

If you did not request this, please ignore this email.

Thanks,  
<span style="color: #49b451;">{{ config('app.name') }}</span>
@endcomponent
