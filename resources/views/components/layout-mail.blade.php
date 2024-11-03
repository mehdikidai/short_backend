
@props(['title'])

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> {{$title}} </title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f9f9f9; margin: 0; padding: 20px;">
    <div
        style="max-width: 600px; margin: 0 auto; background-color: #fff; padding: 20px; border-radius: 10px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);">
        
        {{ $slot }}

        <div style="margin-top: 30px; font-size: 12px; color: #222; text-align: center; opacity: 0.6;">
            <p>Best regards, {{ env('APP_NAME') }}.</p>
        </div>
    </div>
</body>

</html>
