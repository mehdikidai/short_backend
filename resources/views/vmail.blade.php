
<x-layout-mail title="Activate Your Account">

    <h2 style="color: #222;">Hello {{ $name }}!</h2>

    <p style="color: #222; opacity: 0.7;">Thank you for registering. Please use the following code to activate your account:</p>

    <div style="font-size: 24px; color: #0c68e9; font-weight: bold; letter-spacing: 2px;">{{ $code }}</div>
    
    <p style="color: #222; opacity: 0.7;">If you did not request this email, please ignore it.</p>


</x-layout-mail>