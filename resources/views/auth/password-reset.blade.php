<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Password Reset Email | BMS</title>
</head>
<body>
    <h1>Hello {{ $user->name }}</h1>
    <p>You have requested for password reset.</p>
    <p>Please click the link below to reset your password.</p>
    <p>Your Token: {{ $token }}</p>

    <a href="{{ url('/password/reset?token='.$token) }}">Password Reset</a>

    <p>Remember that this token valid for 60 minutes.</p>
    <p>If you did not request, just ignore the mail.</p>

    <h3>Thank You</h3>

</body>
</html>