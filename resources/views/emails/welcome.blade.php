<!DOCTYPE html>
<html lang="en">

<head>
    <title>Welcome Mail</title>
</head>

<body style="font-family: Verdana, Geneva, Tahoma, sans-serif; padding: 20px; background-color: #f4f4f4;">
    <div
        style="max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);">
        <h1 style="text-align: center;"> Greetings,
            <span style="color: #49b451;">
                {{ $user->name }}
            </span>
        </h1>
        <p style="color: #333; font-size: 16px;">Welcome to our application. We're glad to have you on board!</p>
        <p style="color: #333; font-size: 14px;">Before you start, please verify your email address by clicking the
            button below</p>

        <a href="{{ $verificationUrl }}"
            style="display:inline-block; padding:10px 20px; border-radius: 10px; background-color:#212121; color:#fff; text-decoration:none; font-weight:bold;">
            Verify Email
        </a>

        <p style="color: #333; font-size: 14px; margin-top: 20px;">If you didn’t create an account, disregard this email.
        </p>
        <p style="font-weight: bold;">Best regards,</p>
        <p style="color: #49b451; font-weight: bold;">{{ config('app.name') }}</p>
    </div>
</body>

</html>
