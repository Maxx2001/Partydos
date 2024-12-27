<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Reset Request</title>
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
            color: #333333;
        }
        .container {
            width: 100%;
            background-color: #ffffff;
            text-align: center;
            padding: 20px 0;
        }
        .email-content {
            width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            border: 1px solid #dddddd;
        }
        .header {
            padding: 20px;
            text-align: center;
        }
        .header img {
            width: 120px;
        }
        .body {
            padding: 20px;
            text-align: left;
            font-size: 14px;
            line-height: 1.5;
        }
        .body h1 {
            font-size: 24px;
            color: rgb(29 78 216 / var(--tw-text-opacity, 1));
            margin-bottom: 20px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            font-size: 16px;
            color: #ffffff;
            background-color: rgb(29 78 216 / var(--tw-text-opacity, 1));
            text-decoration: none;
            border-radius: 25px;
            margin: 20px 0;
            text-align: center;
        }
        .footer {
            padding: 10px;
            font-size: 12px;
            background-color: #333333;
            color: #ffffff;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="email-content">
        <!-- Header -->
        <div class="header">
            <h1>
                <span>Party</span>
                <span style="color: rgb(29 78 216 / var(--tw-text-opacity, 1)); margin-left: -9px;">dos</span>
            </h1>
        </div>
        <!-- Body -->
        <div class="body">
            <h1>Password Reset Request</h1>
            <p>Hello {{ $user->name }},</p>
            <p>We received a request to reset your password. Click the button below to reset your password:</p>
            <div style="text-align: center;">
                <a href="{{ $url }}" class="button">Reset Password</a>
            </div>
            <p>If you did not request a password reset, please ignore this email or contact support if you have questions.</p>
            <p>Alternatively, you can copy and paste the following link into your browser:</p>
            <p><a href="{{ $url }}" style="color: rgb(29 78 216 / var(--tw-text-opacity, 1));">{{ $url }}</a></p>
            <p>This link will expire in {{ $durationPasswordValidMinutes }} minutes.</p>
            <p>Thank you,</p>
            <p>The Support Team</p>
        </div>
        <!-- Footer -->
        <div class="footer">
            <p>&copy; 2024 Partydos. All rights reserved.</p>
        </div>
    </div>
</div>
</body>
</html>
