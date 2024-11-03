{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>password reset</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);">
        <h2 style="color: #222;">Hello {{ $name }}!</h2>
        <p style="color: #222; opacity: 0.7;">You requested a password reset for your account. Please use the following code to complete the password reset process:</p>
        <div style="font-size: 24px; color: #0c68e9; font-weight: bold; letter-spacing: 2px;">{{ $code }}</div>
        <p style="color: #222; opacity: 0.7;">Please note that this code is only valid for a limited time. If you did not request a password reset, please ignore this message.</p>
        <div style="margin-top: 30px; font-size: 12px; color: #222; text-align: center; opacity: 0.6;">
            <p>Best regards, {{ env('APP_NAME') }}.</p>
        </div>
    </div>
</body>

</html> --}}


<x-layout-mail title="Password Reset">

    <h2 style="color: #222;">Hello {{ $name }}!</h2>

    <p style="color: #222; opacity: 0.7;">You requested a password reset for your account. Please use the following code to complete the password reset process:</p>

    <div style="font-size: 24px; color: #0c68e9; font-weight: bold; letter-spacing: 2px;">{{ $code }}</div>

    <p style="color: #222; opacity: 0.7;">Please note that this code is only valid for a limited time. If you did not request a password reset, please ignore this message.</p>


</x-layout-mail>