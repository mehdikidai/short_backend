<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activate Your Account</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        h2 {
            color: #222;
        }

        p {
            color: #222;
            opacity: 0.7
        }

        .activation-code {
            font-size: 24px;
            color: #0c68e9;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #222;
            opacity: 0.4;
            text-align: center;
            opacity:0.6
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Hello {{ $name }} !</h2>
        <p>Thank you for registering. Please use the following code to activate your account:</p>
        <div class="activation-code">{{ $code }}</div>
        <p>If you did not request this email, please ignore it.</p>
        <div class="footer">
            <p>Best regards, {{ env('APP_NAME') }}.</p>
        </div>
    </div>
</body>

</html>
