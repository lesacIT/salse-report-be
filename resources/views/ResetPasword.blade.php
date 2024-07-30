<!DOCTYPE html>
<html>
<head>
    <title>{{ $content['subject'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #007bff;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>{{ $content['subject'] }}</h1>
        <p>{{ $content['body'] }}</p>
        <p>Click the link below to reset your password:</p>
        <a href="{{ $content['reset_link'] }}" class="button">Reset Password</a>
        <p>If you did not request a password reset, no further action is required.</p>
        <p>Some more text</p>
    </div>
</body>
</html>
