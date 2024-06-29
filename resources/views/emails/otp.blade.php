<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>OTP Verification</title>
</head>
<body>
    <h1>Your OTP is: {{ $otp->token }}</h1>
    <p>Use this OTP to verify your account.</p>
    <p>Thank you</p>
</body>
</html>
