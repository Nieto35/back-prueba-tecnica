<!-- resources/views/mail/auth/verify.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Email Verification</title>
</head>
<body>
    <h1>Hello, {{ $name }}</h1>
    <p>Please click the button below to verify your email address:</p>
    <a href="{{ $url }}" style="display: inline-block; padding: 10px 20px; color: #fff; background-color: #007bff; text-decoration: none; border-radius: 5px;">Verify Email</a>
</body>
</html>
